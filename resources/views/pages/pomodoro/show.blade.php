<h1>HELLO THIS IS POMODORO</h1>

<x-app-layout>
<div>
    <div class="flex justify-between align-center ">
        <h1>Pomodoro</h1>
        <h1>Short Break</h1>
        <h1>Long Break</h1>
    </div>
    <div class="flex justify-between align-center ">
        <h1 id="timerMin">25</h1>
        <h1 class="mx-2">:</h1>
        <h1 class="timerSec">00</h1>
    </div>
    <div>
        <div class="flex justify-between align-center ">
            <button id="startBtn" class="bg-green-500 text-black font-semibold rounded-md shadow-md houver:bg-green-600 focus:outline-none focus::ring-green-400">Start</button>
            <button id="pauseBtn" class="bg-green-500 text-black font-semibold rounded-md shadow-md houver:bg-green-600 focus:outline-none focus::ring-green-400">Pause</button>
            <button id="resetBtn" class="bg-green-500 text-black font-semibold rounded-md shadow-md houver:bg-green-600 focus:outline-none focus::ring-green-400">Reset</button>
        </div>
  
</div>


<script> 
    let timerMin = document.getElementById('timerMin');
    let timerSec = document.getElementById('timerSec');

    let startBtn = document.getElementById('startBtn');
    let pauseBtn = document.getElementById('pauseBtn');
    let resetBtn = document.getElementById('resetBtn');


    let studyBlockTime = 25;
    let min = 5;
    let sec = 60;


    
    let studyRound = 4; 
    let intervalid;


    let isBreak = false;
    let shortBreakVal = 5;
    let longBreakVal = 15;

    function displayTime(min, sec){

        timerMin.textContent = `${String(min).padStart(2, '0')} : ${String(sec).padStart(2, '0')}`;
    }

    function startTimer(){
        intervalid = setInterval(()=>{
            // if (isBreak ==false){
            //     min = studyBlockTime;
            // }
            // if (isBreak == true || studyRound > 0){ 
            //     min = shortBreakVal;
            // }
            // if (isBreak == true && studyRound < 0){
            //     min = longBreakVal;
            // }

            if(sec>0){
                sec--;

            } 
            else if(min > 0) {
                min --;
                sec = 59;
            }
            else {
                clearInterval(intervalid);

                if(isBreak) {
                    min = studyRound > 0 ? shortBreakVal : longBreakVal;
                } else {
                    min = studyBlockTime; 
                    studyRound--;
                }

                startTimer();
            }
            displayTime(min, sec);
        }, 10)
    }



    startBtn.addEventListener('click', startTimer);

</script>

</x-app-layout>

