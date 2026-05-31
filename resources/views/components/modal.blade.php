@props(['id', 'title' => ''])

<div id="{{ $id }}" class="group">
    <div class="nb-modal-overlay"></div>
    <div class="nb-bottom-sheet flex flex-col">
        @if($title)
        <div class="flex justify-between items-center p-4 border-b-2 border-[#1A1A2E] sticky top-0 bg-white z-10">
            <h3 class="font-heading font-bold text-lg">{{ $title }}</h3>
            <button type="button" onclick="closeModal('{{ $id }}')" class="text-2xl font-bold w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100">&times;</button>
        </div>
        @else
        <div class="flex justify-end p-2 sticky top-0 bg-white z-10 rounded-t-2xl">
            <button type="button" onclick="closeModal('{{ $id }}')" class="text-2xl font-bold w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100">&times;</button>
        </div>
        @endif
        
        <div class="p-4 overflow-y-auto">
            {{ $slot }}
        </div>
    </div>
</div>
