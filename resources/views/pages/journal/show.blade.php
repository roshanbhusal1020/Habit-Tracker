<x-app-layout>
    <div class="container mx-auto p-4">
        <!-- Date Navigation -->
        <div class="flex justify-between items-center mb-6">
            <div>
                @if($previousDate)
                    <a href="{{ route('journal.show', ['date' => $previousDate->format('Y-m-d')]) }}" 
                       class="bg-gray-200 px-4 py-2 rounded">
                        ← Previous
                    </a>
                @endif
            </div>
            
            <h2 class="text-2xl font-bold">
                {{ $currentDate->format('F d, Y') }}
            </h2>
            
            <div>
                @if($nextDate)
                    <a href="{{ route('journal.show', ['date' => $nextDate->format('Y-m-d')]) }}"
                       class="bg-gray-200 px-4 py-2 rounded">
                        Next →
                    </a>
                @endif
            </div>
        </div>

        <!-- Calendar -->
        <div class="mb-8">
            <div id="calendar" class="bg-white p-4 rounded shadow"></div>
        </div>

        <!-- Journal Entry Form (only show if current date is today) -->
        @if($currentDate->isToday())
        <div class="mb-8">
            <h1 class="text-2xl font-bold mb-4">New Entry</h1>
            <form action="{{ route('journal.store') }}" method="POST" class="bg-white p-6 rounded shadow">
                @csrf
                <div class="mb-4">
                    <textarea id="convert_text" 
                              name="content"
                              class="w-full p-2 border rounded"
                              rows="6"
                              placeholder="Start writing or use voice input..."
                              required></textarea>
                </div>

                <div class="flex justify-between">
                    <button type="button" 
                            id="click_to_convert"
                            class="bg-blue-500 text-white px-4 py-2 rounded">
                        Start Voice Entry
                    </button>
                    <button type="submit"
                            class="bg-green-500 text-white px-4 py-2 rounded">
                        Save Entry
                    </button>
                </div>
            </form>
        </div>
        @endif

        <!-- Entries for Selected Date -->
        <div>
            <h2 class="text-xl font-bold mb-4">Entries for {{ $currentDate->format('F d, Y') }}</h2>
            @forelse($todayEntries as $entry)
            <div class="bg-white p-4 rounded shadow mb-4">
                <div class="mb-2">
                    <span class="text-gray-600">{{ $entry->created_at->format('h:i A') }}</span>
                </div>
                <p>{{ $entry->content }}</p>
            </div>
            @empty
            <p class="text-gray-500">No entries for this date.</p>
            @endforelse
        </div>
    </div>


    <script>
        // Voice recognition code
        let click_to_convert = document.getElementById('click_to_convert');
        let convert_text = document.getElementById('convert_text');

        click_to_convert?.addEventListener('click', function(){
            var speech = true; 
            window.SpeechRecognition = window.webkitSpeechRecognition; 
            const recognition = new SpeechRecognition();

            recognition.addEventListener('result', e => {
                const transcript = Array.from(e.results)
                    .map(result => result[0])
                    .map(result => result.transcript)

                convert_text.value = transcript;
            });

            if(speech == true){
                recognition.start();
            }
        });

        // Calendar initialization
        const datesWithEntries = {!! $datesWithEntries !!}; {{--// I am passing here raw data here by passing into !! $datesWithEntries instead of {{   }} becuase I need raw date like "
// ["2025-01-19","2025-01-19"] I could also wrap insde @json_encode; I am adding blade comment out because js comment out alone wouldnt work --}}
            
        const customStyles = document.createElement('style');
        //basically .flatpickr stuff are part of this flatpickr caleneder, it automatically creates these classes.
            customStyles.textContent = `
                .flatpickr-calendar { 
                    color: black !important;
                }
                .flatpickr-day {
                    color: black !important;
                }
                .flatpickr-weekday {
                    color: black !important;
                }
                .flatpickr-monthDropdown-months {
                    color: black !important;
                }
                .flatpickr-current-month {
                    color: black !important;
                }
                .numInputWrapper {
                    color: black !important;
                }
            `;
    document.head.appendChild(customStyles);

    flatpickr("#calendar", {
            inline: true,
            dateFormat: "Y-m-d",
            defaultDate: "{{ $currentDate->format('Y-m-d') }}",
            enable: datesWithEntries,
            onChange: function(selectedDates, dateStr) {
                window.location.href = `/journal?date=${dateStr}`;
            }
        });
    </script>
</x-app-layout>