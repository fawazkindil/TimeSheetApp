<div {{$attributes->merge(['class' => 'absolute top-0 right-0 w-full h-full bg-gray-800 bg-opacity-50 flex flex-row justify-center items-center'])}}>
    <div class="bg-white rounded">
        <span class="float-right text-red-800 cursor-pointer px-2 py-1 font-extrabold text-xl" onclick="toggleModal();">&times;</span>
        {{$slot}}
    </div>
</div>
