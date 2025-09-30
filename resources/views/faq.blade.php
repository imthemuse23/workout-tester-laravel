<x-layouts title="FAQ">
    <div class="max-w-3xl mx-auto py-12 px-4">
        <h1 class="text-3xl font-bold text-center text-blue-600 mb-10">
            Frequently Asked Questions
        </h1>

        @php
            $faqs = [
                [
                    'question' => 'How do I register?',
                    'answer' => 'Click the register button in the top menu, then fill in the required information.',
                ],
                [
                    'question' => 'Is this application free?',
                    'answer' => 'Yes, Workout Tracker can be used for free.',
                ],
                [
                    'question' => 'Is my data safe?',
                    'answer' => 'We use an encryption system to ensure the security of your data.',
                ],
            ];
        @endphp

        <div class="space-y-4">
            @foreach ($faqs as $faq)
                <div x-data="{ open: false }" class="border rounded-xl shadow-sm bg-white">
                    <button @click="open = !open"
                        class="w-full flex justify-between items-center px-6 py-4 text-left font-medium text-lg text-gray-800 hover:bg-blue-50 rounded-xl transition">
                        <span>{{ $faq['question'] }}</span>
                        <svg :class="open ? 'rotate-180 text-blue-600' : 'rotate-0 text-gray-400'"
                            class="w-5 h-5 transform transition-transform duration-300" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="px-6 pb-4 text-gray-600">
                        {{ $faq['answer'] }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-layouts>
