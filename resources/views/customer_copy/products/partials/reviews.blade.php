@if(isset($reviews) && $reviews->count() > 0)
    <div class="space-y-6">
        @foreach($reviews as $review)
        <div class="border-b border-gray-100 pb-6 last:border-0 last:pb-0">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 font-bold text-lg">
                    {{ strtoupper(substr($review->user_name, 0, 1)) }}
                </div>
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="font-bold text-gray-800">{{ $review->user_name }}</h4>
                        <span class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="flex items-center mb-2">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="text-sm {{ $i <= $review->rating ? 'text-amber-400' : 'text-gray-300' }}">★</span>
                        @endfor
                    </div>
                    <p class="text-gray-600">{{ $review->review }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@else
    <p class="text-gray-500 italic">No reviews yet.</p>
@endif
