<x-app-layout>
    <div class="container mx-auto p-8 max-w-2xl text-center">

    <div class="mb-4 flex justify-center gap-4">
    <div>
        <label for="focusDuration" class="block text-sm">Focus (min)</label>
        <input type="number" id="focusDuration" value="25" class="border rounded px-2 py-1 w-20 text-center">
    </div>
    <div>
        <label for="shortBreakDuration" class="block text-sm">Short Break (min)</label>
        <input type="number" id="shortBreakDuration" value="5" class="border rounded px-2 py-1 w-20 text-center">
    </div>
    <div>
        <label for="longBreakDuration" class="block text-sm">Long Break (min)</label>
        <input type="number" id="longBreakDuration" value="15" class="border rounded px-2 py-1 w-20 text-center">
    </div>
</div>
 
    <div class="mb-4">
        <h2 id="modeDisplay" class="text-2x1 font-bold">Focis time</h2>
        <p>Pomodoros: <span id="pomodoroCount">0</span></p>
    </div>

        <div class="text-6xl font-bold mb-8">
            <span id="minutes">25</span>
            <span>:</span>
            <span id="seconds">00</span>
        </div>

        <button id="startBtn" 
                class="bg-green-500 hover:bg-green-600 text-black font-bold py-2 px-6 rounded-lg">
            Start
        </button>
        <button id="stopBtn" 
                class="bg-green-500 hover:bg-green-600 text-black font-bold py-2 px-6 rounded-lg">
            Stop
        </button>


        <input type="text" id="taskInput" class="border rounded-lg px-4 py-2 w-full max-w-md"> </input>
    </div>


    <audio id="alarmSound" src="{{ asset('sounds/alert.mp3') }}" preload="auto"></audio>
    <script>
class PomodoroTimer {
    constructor() {
        this.focusInput = document.getElementById('focusDuration');
        this.shortBreakInput = document.getElementById('shortBreakDuration');
        this.longBreakInput = document.getElementById('longBreakDuration');

        this.TIMER_MODES = {}; // initialize empty, will be set next line
        this.setModesFromInputs(); // call method here

        this.currentSessionId = null;
        this.taskInput = document.getElementById('taskInput');
        this.minutesDisplay = document.getElementById('minutes'); 
        this.secondsDisplay = document.getElementById('seconds');
        this.startButton = document.getElementById('startBtn');
        this.stopButton = document.getElementById('stopBtn');
        this.modeDisplay = document.getElementById('modeDisplay');
        this.pomodoroCountDisplay = document.getElementById('pomodoroCount');
    
        this.currentMode = this.TIMER_MODES.POMODORO;
        this.minutes = this.currentMode.duration;
        this.seconds = 0;
        this.timerID = null;
        this.isRunning = false;
        this.pomodoroCount = 0;
        this.originalMinutes = this.minutes; 

        this.setupEventListeners();
        this.updateDisplay();
    }

    setModesFromInputs() {
        const prevMode = this.currentMode ? this.currentMode.name : 'Focus Time';

        this.TIMER_MODES = {
            POMODORO: {
                duration: parseInt(this.focusInput.value) || 25,
                name: 'Focus Time',
            },
            SHORT_BREAK: {
                duration: parseInt(this.shortBreakInput.value) || 5,
                name: 'Short Break',
            },
            LONG_BREAK: {
                duration: parseInt(this.longBreakInput.value) || 15,
                name: 'Long Break',
            }
        };


        switch (prevMode) {
            case 'Short Break':
                this.currentMode = this.TIMER_MODES.SHORT_BREAK;
                break;
            case 'Long Break':
                this.currentMode = this.TIMER_MODES.LONG_BREAK;
                break;
            default:
                this.currentMode = this.TIMER_MODES.POMODORO;
        }

        if (!this.isRunning) {
            this.minutes = this.currentMode.duration;
            this.seconds = 0;
            this.originalMinutes = this.minutes;
        }
    }

    setupEventListeners() {
        this.focusInput.addEventListener('change', () => {
            this.setModesFromInputs();
            this.updateDisplay();
        });
        
        this.shortBreakInput.addEventListener('change', () => {
            this.setModesFromInputs();
            this.updateDisplay();
        });
        
        this.longBreakInput.addEventListener('change', () => {
            this.setModesFromInputs();
            this.updateDisplay();
        });

        this.startButton.addEventListener('click', this.handleStartPause.bind(this));
        
        this.stopButton.addEventListener('click', () => {
            this.resetTimer();
        });
    }

    async startTimer() {

        if (this.timerID !== null) {
            return;
        }


        if (this.currentSessionId === null) {
            try {
                const response = await fetch('/api/pomodoro/start', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        task_name: this.taskInput.value || this.currentMode.name,
                        type: this.currentMode.name,
                        duration: this.originalMinutes 
                    })
                });

                const data = await response.json();
                this.currentSessionId = data.id;
                console.log('Started session:', this.currentSessionId);
            } catch (error) {
                console.error('Failed to start session:', error);
            }
        }


        this.timerID = setInterval(() => {
            if (this.seconds > 0) {
                this.seconds--;
            } else if (this.minutes > 0) {
                this.minutes--;
                this.seconds = 59;
            } else {
                this.stopTimer();
                this.moveToNextState();
            }

            this.updateDisplay();
        }, 1000); 
    }

    stopTimer() {
        if (this.timerID !== null) {
            clearInterval(this.timerID);
            this.timerID = null;
        }
    }

    resetTimer() {
        this.stopTimer();
        
        this.currentSessionId = null;
        
        this.isRunning = false;
        this.startButton.textContent = 'Start';
        this.minutes = this.currentMode.duration;
        this.seconds = 0;
        this.originalMinutes = this.minutes;
        this.updateDisplay();
    }

    async moveToNextState() {
        const alarm = document.getElementById('alarmSound');
        if (alarm) {
            alarm.play().catch(e => {
                console.warn('Alarm sound play was blocked:', e);
            });
        }

        if (this.currentSessionId) {
            try {
                await fetch('/api/pomodoro/complete', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        session_id: this.currentSessionId
                    })
                });
            } catch (error) {
                console.error('Failed to complete session:', error);
            }

            this.currentSessionId = null;
        }

        if (this.currentMode === this.TIMER_MODES.POMODORO) {
            this.pomodoroCount++;
            if (this.pomodoroCount % 4 === 0) {
                this.currentMode = this.TIMER_MODES.LONG_BREAK;
                alert("Time for a long break");
            } else {
                this.currentMode = this.TIMER_MODES.SHORT_BREAK;
                alert("Time for a short break");
            }
        } else {
            this.currentMode = this.TIMER_MODES.POMODORO;
            alert("Time to focus");
        }

        this.minutes = this.currentMode.duration;
        this.seconds = 0;
        this.originalMinutes = this.minutes;
        this.isRunning = false;
        this.startButton.textContent = 'Start';
        this.updateDisplay();
    }

    handleStartPause() {
        if (this.isRunning) {
            this.stopTimer();
            this.isRunning = false;
            this.startButton.textContent = 'Resume';
        } else {
            this.startTimer();
            this.isRunning = true;
            this.startButton.textContent = 'Pause';
        }
        this.updateDisplay();
    }

    updateDisplay() {
        const paddedMinutes = String(this.minutes).padStart(2, '0');
        const paddedSeconds = String(this.seconds).padStart(2, '0');

        this.minutesDisplay.textContent = paddedMinutes;
        this.secondsDisplay.textContent = paddedSeconds;
        this.modeDisplay.textContent = this.currentMode.name;
        this.pomodoroCountDisplay.textContent = this.pomodoroCount;

        document.title = `${paddedMinutes}:${paddedSeconds} - ${this.currentMode.name}`;
    }
}


 

     const timer = new PomodoroTimer();
    </script>



</x-app-layout>