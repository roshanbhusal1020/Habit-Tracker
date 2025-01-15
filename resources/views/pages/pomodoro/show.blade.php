<x-app-layout>
    <div class="container mx-auto p-8 max-w-2xl text-center">


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

    <script>
     


     class PomodoroTimer {
        constructor () {
            this.TIMER_MODES = {
                POMODORO: {
                    duration: 25, 
                    name: 'Focus Time',

                }, 
                SHORT_BREAK: {
                    duration: 5, 
                    name: ' Short Break',

                    
                }, 
                LONG_BREAK: {
                    duration:15,
                    name: 'Long Break',

                    }
            }

            this.currentSessionId = null;
            this.taskInput = document.getElementById('taskInput');
            this.minutesDisplay = document.getElementById('minutes'); 
            this.secondsDisplay = document.getElementById('seconds')
            this.startButton = document.getElementById('startBtn')
            this.stopButton = document.getElementById('stopBtn')
            this.modeDisplay = document.getElementById('modeDisplay')
            this.pomodoroCountDisplay = document.getElementById('pomodoroCount')
        
            this.currentMode = this.TIMER_MODES.POMODORO
            this.minutes = this.currentMode.duration
            this.seconds = 0
            this.timerID= null
            this.isRunning = false
            this.pomodoroCount = 0; 


            this.setupEventListeners()
            this.updateDisplay()
        }

        setupEventListeners() {

            this.startButton.addEventListener('click', this.handleStartPause.bind(this))
            this.stopButton.addEventListener('click', this.handleStartPause.bind(this))
            
        }

        async startTimer() {


            if(this.timerID !== null ){
                return
            }


            try {
                const response = await fetch('/api/pomodoro/start', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    task_name: this.taskInput.value,
                    type: this.currentMode.name,
                    duration: this.currentMode.duration
                })
            });

            const data = await response.json();
            this.currentSessionId = data.id;  // Store the session ID
            console.log(this.currentSessionId)

        } catch (error) {
            console.error('Failed to start session:', error);
        }


            this.timerID = setInterval(()=>{
                if(this.seconds > 0 ){
                    this.seconds --

                    
                }
                else if(this.minutes > 0) {
                    this.minutes-- 
                    this.seconds = 59

                }
                else {
                    this.stopTimer()
                    this.moveToNextState()
                }


                this.updateDisplay();
            }, 1)
        }


        stopTimer() {
            if(this.timerID !==null) {
                clearInterval(this.timerID)
                this.timerID = null;
                // theres a problem, when I click stop it starts the time
            }
        }

        async moveToNextState () {

            console.log(this.currentSessionId)
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
        }


            if(this.currentMode === this.TIMER_MODES.POMODORO) {
                this.pomodoroCount ++
                if (this.pomodoroCount % 4 ===0) {
                    this.currentMode = this.TIMER_MODES.LONG_BREAK;
                    alert("Time for a long break")
                } else {
                    this.currentMode = this.TIMER_MODES.SHORT_BREAK
                    alert("Time for a short break")
                }
            }
            else {
                this.currentMode = this.TIMER_MODES.POMODORO
                alert("Time to focus")
            }

            this.minutes=this.currentMode.duration;
            this.seconds = 0; 
            this.isRunning = false
            this.startButton.textContent = 'Start'
            this.updateDisplay()
        }


        handleStartPause() {
            if (this.isRunning) {
                this.stopTimer()
                this.isRunning =false
                this.startButton.textContent = 'Start'
            } else {
                this.startTimer()
                this.isRunning =true
                this.startButton.textContent = 'Pause'
            }
            this.updateDisplay();
        }

        updateDisplay() {
            this.minutesDisplay.textContent = String(this.minutes).padStart(2, '0');
            this.secondsDisplay.textContent = String(this.seconds).padStart(2, '0');
            this.modeDisplay.textContent = this.currentMode.name;
            this.pomodoroCountDisplay.textContent = this.pomodoroCount; 
        }
     }

     const timer = new PomodoroTimer();
    </script>



</x-app-layout>