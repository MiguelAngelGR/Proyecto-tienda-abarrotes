<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalles del Producto') }}
            </h2>
            <div>
                <a href="{{ route('producto.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                    Volver
                </a>
                @if (Auth::user()->role === 'admin')
                <a href="{{ route('producto.edit', $product) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
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
                            <h3 class="text-lg font-semibold mb-4">Información del Producto</h3>
                            
                            <div class="mb-6">
                                @if ($product->image)
                                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-full max-w-md object-cover rounded">
                                @else
                                    <div class="w-full h-64 bg-gray-200 flex items-center justify-center rounded">
                                        <span class="text-gray-500">Sin imagen</span>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="mb-4">
                                <p class="text-sm text-gray-600">Nombre:</p>
                                <p class="font-semibold">{{ $product->name }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <p class="text-sm text-gray-600">Código de Barras:</p>
                                <p class="font-semibold">{{ $product->barcode ?? 'N/A' }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <p class="text-sm text-gray-600">Categoría:</p>
                                <p class="font-semibold">{{ $product->category->name }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <p class="text-sm text-gray-600">Proveedor:</p>
                                <p class="font-semibold">{{ $product->supplier ? $product->supplier->name : 'N/A' }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <p class="text-sm text-gray-600">Precio:</p>
                                <p class="font-semibold">${{ number_format($product->price, 2) }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <p class="text-sm text-gray-600">Stock Total:</p>
                                <p class="font-semibold">{{ $product->stock }} unidades</p>
                            </div>
                            
                            <div class="mb-4">
                                <p class="text-sm text-gray-600">Descripción:</p>
                                <p>{{ $product->description ?? 'Sin descripción' }}</p>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold mb-4">Inventario y Fechas de Caducidad</h3>
                            
                            @if (Auth::user()->role === 'admin')
                            <div class="bg-gray-100 p-4 rounded mb-4">
                                <h4 class="font-semibold mb-2">Agregar al Inventario</h4>
                                <form action="{{ route('producto.addInventory', $product) }}" method="POST">
                                    @csrf
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="quantity" class="block text-sm font-medium text-gray-700">Cantidad</label>
                                            <input type="number" name="quantity" id="quantity" min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                        </div>
                                        <div>
                                            <label for="expiry_date" class="block text-sm font-medium text-gray-700">Fecha de Caducidad</label>
                                            <input type="date" name="expiry_date" id="expiry_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                            Agregar
                                        </button>
                                    </div>
                                </form>
                            </div>
                            @endif

                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white">
                                    <thead>
                                        <tr>
                                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                Cantidad
                                            </th>
                                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                Fecha de Caducidad
                                            </th>
                                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                Estado
                                            </th>
                                            @if (Auth::user()->role === 'admin')
                                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                Acciones
                                            </th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($inventoryItems as $item)
                                        <tr>
                                            <td class="py-2 px-4 border-b border-gray-200">{{ $item->quantity }}</td>
                                            <td class="py-2 px-4 border-b border-gray-200">{{ $item->expiry_date->format('d/m/Y') }}</td>
                                            <td class="py-2 px-4 border-b border-gray-200">
                                                @if ($item->status === 'expired')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                        Caducado
                                                    </span>
                                                @elseif ($item->status === 'warning')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        Próximo a caducar
                                                    </span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        Vigente
                                                    </span>
                                                @endif
                                            </td>
                                            @if (Auth::user()->role === 'admin')
                                            <td class="py-2 px-4 border-b border-gray-200">
                                                <form action="{{ route('inventory.remove', $item) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:underline" onclick="return confirm('¿Estás seguro de eliminar este elemento del inventario?')">
                                                        Eliminar
                                                    </button>
                                                </form>
                                            </td>
                                            @endif
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="{{ Auth::user()->role === 'admin' ? '4' : '3' }}" class="py-4 px-4 border-b border-gray-200 text-center">
                                                No hay elementos de inventario registrados.
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>