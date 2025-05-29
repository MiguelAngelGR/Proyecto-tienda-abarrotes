<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalles de Categoría') }}
            </h2>
            <div>
                <a href="{{ route('categories.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                    Volver
                </a>
                @if (Auth::user()->role === 'admin')
                <a href="{{ route('categories.edit', $category) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                    Editar
                </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Información de la Categoría</h3>
                            
                            <div class="mb-4">
                                <p class="text-sm text-gray-600">Nombre:</p>
                                <p class="font-semibold">{{ $category->name }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <p class="text-sm text-gray-600">Descripción:</p>
                                <p>{{ $category->description ?? 'Sin descripción' }}</p>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold mb-4">Productos en esta Categoría</h3>
                            
                            @if ($category->producto->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full bg-white">
                                        <thead>
                                            <tr>
                                                <th class="py-2 px-4 border-b border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                    Nombre
                                                </th>
                                                <th class="py-2 px-4 border-b border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                    Código
                                                </th>
                                                <th class="py-2 px-4 border-b border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                    Precio
                                                </th>
                                                <th class="py-2 px-4 border-b border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                    Stock
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($category->producto as $product)
                                            <tr>
                                                <td class="py-2 px-4 border-b border-gray-200">
                                                    <a href="{{ route('producto.show', $product) }}" class="text-blue-500 hover:underline">
                                                        {{ $product->name }}
                                                    </a>
                                                </td>
                                                <td class="py-2 px-4 border-b border-gray-200">{{ $product->barcode ?? 'N/A' }}</td>
                                                <td class="py-2 px-4 border-b border-gray-200">${{ number_format($product->price, 2) }}</td>
                                                <td class="py-2 px-4 border-b border-gray-200">{{ $product->stock }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-gray-500">No hay productos en esta categoría.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>