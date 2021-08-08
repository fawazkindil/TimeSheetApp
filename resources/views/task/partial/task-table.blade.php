<table class="w-full">
    <thead class="font-bold">
        <tr class="border-b-2 border-gray-600">
            <th>
                Description
            </th>
            <th>
                Time
            </th>
            <th>
                Action
            </th>
        </tr>
    </thead>
    <tbody class="">
        @forelse ($tasks as $task)
            <tr class="border-b border-gray-100">
                <td class="py-1">
                    {{$task->description}}
                </td>
                <td class="py-1">
                    <div class="flex flex-row items-center justify-center">
                        <div class="bg-blue-400 text-white text-xs rounded px-1 py-1 whitespace-nowrap">
                            {{$task->hours . ' hours'}}
                        @if ($task->minutes !== 0)
                            {{$task->minutes.' minutes'}}
                        @endif
                        </div>
                    </div>
                </td>
                <td class="py-1">
                    <div class="flex flex-row items-center">
                        <button type="button" class="px-1 border-2 border-green-500 rounded text-green-500" onclick="toggleModal();populateEditTask({{$task->id}});">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="px-1 border-2 border-red-500 rounded text-red-500" onclick="deleteTask({{$task->id}});">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3">
                    No Task Found
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
