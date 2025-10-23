@props(['items'])

<!-- パンくずリスト - レスポンシブ対応 -->
<nav class="flex mb-6" aria-label="Breadcrumb">
    <ol class="flex flex-wrap items-center gap-1 sm:gap-2 md:gap-3">
        @foreach($items as $index => $item)
            <li class="flex items-center">
                @if($index > 0)
                    <!-- セパレーター -->
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 text-gray-400 mx-1 sm:mx-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                @endif
                
                @if(isset($item['url']) && !isset($item['current']))
                    <!-- リンク項目 -->
                    <a href="{{ $item['url'] }}" class="inline-flex items-center text-xs sm:text-sm font-medium text-gray-700 hover:text-indigo-600 break-all">
                        @if($index === 0 && isset($item['icon']))
                            <!-- ホームアイコン -->
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                        @endif
                        <span class="truncate max-w-[150px] sm:max-w-[200px] md:max-w-none">{{ $item['title'] }}</span>
                    </a>
                @else
                    <!-- 現在のページ項目 -->
                    <span class="inline-flex items-center text-xs sm:text-sm font-medium text-gray-500 break-all" aria-current="page">
                        <span class="truncate max-w-[150px] sm:max-w-[200px] md:max-w-none">{{ $item['title'] }}</span>
                    </span>
                @endif
            </li>
        @endforeach
    </ol>
</nav> 