<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('TodoList') }}
        </h2>
    </x-slot>

    <form method="POST" action="{{ route('tasks.store') }}" class="flex flex-col space-y-4 text-gray-500">

        <h3>Ajouter une tache</h3>
        @csrf

        <div>
            <x-input-label for="name" :value="__('Nom')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')"
                autofocus />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="flex justify-end">
            <x-primary-button type="submit">
                {{ __('Ajouter tache') }}
            </x-primary-button>
        </div>
    </form>

    <ul>
        @foreach ($tasks as $task)
            <li>
                <div class="flex items-center">
                    {{ $task->name }}

                    <form class="taskForm" method="POST" action="{{ route('tasks.update', $task->id) }}">
                        @csrf
                        @method('PATCH')
                        <input type="checkbox" class="taskCheckbox" data-task-id="{{ $task->id }}"
                            @checked($task->is_done)>
                    </form>


                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button x-data="{ id: {{ $task->id }} }"
                            x-on:click.prevent="window.selected = id; $dispatch('open-modal', 'confirm-article-deletion');"
                            type="submit" class="text-red-400">Delete</button>
                    </form>
                </div>
            </li>
        @endforeach
    </ul>

    <x-modal name="confirm-article-deletion" focusable>
        <form method="post" onsubmit="event.target.action= '/tasks/' + window.selected" class="p-6">
            @csrf
            @method('DELETE')

            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Êtes-vous sûr de vouloir supprimer cet article ?
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Cette action est irréversible. Toutes les données seront supprimées.
            </p>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Annuler
                </x-secondary-button>

                <x-danger-button class="ml-3" type="submit">
                    Supprimer
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</x-app-layout>

<script>
    const checkboxes = document.querySelectorAll('.taskCheckbox');

    // Parcourez chaque case à cocher et ajoutez un écouteur d'événements
    checkboxes.forEach((checkbox) => {
        checkbox.addEventListener('click', function() {
            // Sélectionnez le formulaire parent de la case à cocher cliquée
            const form = this.closest('.taskForm');

            // Soumettez le formulaire
            if (form) {
                form.submit();
            }
        });
    });
</script>
