<div class="flex space-x-2">
    <a href="{{ route('admin.appointments.edit', $appointment) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded">
        <i class="fa-solid fa-pen-to-square"></i>
    </a>
    <form action="{{ route('admin.appointments.destroy', $appointment) }}" method="POST" class="delete-form inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded">
            <i class="fa-solid fa-trash"></i>
        </button>
    </form>
</div>
