@extends('layouts.main')

@section('contentAdmin')
    <div class="p-4">
        <div class="flex justify-between items-center">
            <h1 class="text-lg font-semibold text-gray-800 mb-3">Data Review</h1>
        </div>
        <div class="mt-6">
            <div class="overflow-auto rounded-lg shadow block w-full mt-5 md:mt-0 md:col-span-2">
                <table class="table-auto w-full">
                    <thead class="bg-gray-50 border-b-2 border-gray-200">
                        <tr>
                            <th class="w-10 p-3 text-sm font-semibold tracking-wide text-left">#</th>
                            <th class="w-32 p-3 text-sm font-semibold tracking-wide text-left">User Name</th>
                            <th class="w-32 p-3 text-sm font-semibold tracking-wide text-left">Book Title</th>
                            <th class="w-64 p-3 text-sm font-semibold tracking-wide text-left">Review</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @if ($reviews->isEmpty())
                            <tr>
                                <td colspan="4">
                                    <p class="text-sm p-5">Tidak terdapat review</p>
                                </td>
                            </tr>
                        @endif
                        @foreach ($reviews as $review)
                            <tr>
                                <td class="p-3 text-sm text-gray-700 whitespace-nowrap">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="p-3 text-sm text-gray-700 whitespace-nowrap">
                                    {{ $review->user->name }}
                                </td>
                                <td class="p-3 text-sm text-gray-700 whitespace-nowrap">
                                    {{ $review->book->title }}
                                </td>
                                <td class="p-3 text-sm text-gray-700 whitespace-nowrap">
                                    {{ $review->review }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
