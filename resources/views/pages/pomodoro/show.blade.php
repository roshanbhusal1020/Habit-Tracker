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
    </div>

    <script>
        let minutesDisplay = document.getElementById('minutes');
        let secondsDisplay = document.getElementById('seconds');
        let startButton = document.getElementById('startBtn');
        let stopBtn = document.getElementById('stopBtn');

        let modeDisplay = document.getElementById('modeDisplay');
        let pomodoroCountDisplay = document.getElementById('pomodoroCount');

        const TIMER_MODES = {
            POMODORO: {
                duration: 7,
                name: 'Focus Time'
            },
            SHORT_BREAK: {
                duration:5, 
                name: 'Short Break'
            }
        }

        let currentMode = TIMER_MODES.POMODORO; 

        let pomodoroCount = 0; 

        let minutes = currentMode.duration;
        let seconds = 0;
        let timerId = null;

        let isRunning = false;

        startButton.addEventListener('click', handleStartPause);

        stopBtn.addEventListener('click', stopTimer);


        function startTimer() {
            timerId = setInterval(function() {
                if (seconds > 0) {
                    seconds = seconds - 1;
                }
                else if (minutes > 0) {
                    minutes = minutes - 1;
                    seconds = 59;
                }
                else {
                    stopTimer();
                    moveToNextState()

                }

                minutesDisplay.textContent = minutes;
                secondsDisplay.textContent = seconds < 10 ? '0' + seconds : seconds;
            }, 10);
        }


        function handleStartPause() {
            if(isRunning) {
                stopTimer(); 
                isRunning = false; 
                startButton.textContent = 'Start';
            } else {
                startTimer(); 
                isRunning = true; 
                startButton.textContent = 'Pause' 
            }
        }

        function stopTimer() {
            console.log(timerId);
            clearInterval(timerId);
            console.log(timerId);
            timerId = null;
            console.log(timerId);
        }



        function moveToNextState() {
            if(currentMode === TIMER_MODES.POMODORO) {
                pomodoroCount ++
                currentMode = TIMER_MODES.SHORT_BREAK;

            } 
            else {
                currentMode = TIMER_MODES.POMODORO;
            }

            minutes = currentMode.duration;
            seconds = 0;

            updateDisplay()
        }


        function updateDisplay() {
            minutesDisplay.textContent = String(minutes).padStart(2, '0')
            secondsDisplay.textContent = String(seconds).padStart(2, '0')


            modeDisplay.textContent = currentMode.name;
            pomodoroCountDisplay.textContent = pomodoroCount;
        }
    </script>



</x-app-layout>