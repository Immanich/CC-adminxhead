@extends('layouts.admin')

@section('content')
    <div class="container mx-auto my-6 p-6 bg-white shadow-md rounded-lg">
        <a href="{{ route('pending.services') }}" class="self-start  rounded-full bg-gray-600 py-2 px-4 text-white hover:bg-slate-700">
            <i class="bi bi-arrow-left"> Back</i>
        </a>
        <h1 class="text-2xl font-semibold text-center text-gray-800">Service Details</h1>
        <p class="text-lg font-semibold text-center p-2 text-yellow-600 mt-1">Status: Pending</p>

        <table class="w-full border border-gray-300 rounded-lg shadow-md mt-6">
            <tbody>
                <!-- Service Name -->
                <tr class="bg-gray-50">
                    <td class="border border-gray-300 px-4 py-3 font-bold text-gray-800">Service Name:</td>
                    <td class="border border-gray-300 px-4 py-3 text-gray-700">{{ $service->service_name }}</td>
                </tr>

                <!-- Description -->
                <tr>
                    <td class="border border-gray-300 px-4 py-3 font-bold text-gray-800">Description:</td>
                    <td class="border border-gray-300 px-4 py-3 text-gray-700">{!! nl2br(e($service->description)) !!}</td>
                </tr>

                <!-- Classification -->
                <tr class="bg-gray-50">
                    <td class="border border-gray-300 px-4 py-3 font-bold text-gray-800">Classification:</td>
                    <td class="border border-gray-300 px-4 py-3 text-gray-700">{{ $service->classification }}</td>
                </tr>

                <!-- Type of Transaction -->
                <tr>
                    <td class="border border-gray-300 px-4 py-3 font-bold text-gray-800">Type of Transaction:</td>
                    <td class="border border-gray-300 px-4 py-3 text-gray-700">{{ $service->transaction->type_of_transaction }}</td>
                </tr>

                <!-- Checklist of Requirements and Where to Secure -->
                <tr class="bg-gray-200">
                    <td class="border border-gray-300 px-4 py-3 font-bold text-gray-900">Checklist of Requirements</td>
                    <td class="border border-gray-300 px-4 py-3 font-bold text-gray-900">Where to Secure</td>
                </tr>

                @foreach(json_decode($service->checklist_of_requirements, true) ?? [] as $index => $checklist)
                    <tr class="{{ $loop->odd ? 'bg-gray-50' : '' }}">
                        <td class="border border-gray-300 px-4 py-3 text-gray-700">{{ $checklist }}</td>
                        <td class="border border-gray-300 px-4 py-3 text-gray-700">
                            @php
                                $whereToSecureList = json_decode($service->where_to_secure, true) ?? [];
                            @endphp
                            {{ $whereToSecureList[$index] ?? 'N/A' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- <div class="mt-6 flex justify-start space-x-4">
            <a href="{{ route('pending.services') }}" class="px-4 py-2 text-white bg-blue-500 hover:bg-blue-700 rounded-lg">
                Back to Pending Services
            </a>
        </div> --}}
    </div>
@endsection
