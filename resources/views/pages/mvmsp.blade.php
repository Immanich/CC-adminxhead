@extends('layouts.admin')

@section('content')
    <div class="container bg-white p-10 rounded-lg shadow-md relative">
        <!-- Logo -->
        <div class="absolute top-4 right-4">
            {{-- <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="w-24 h-24"> --}}
        </div>

        <!-- Heading -->
        <div class="flex justify-center mb-8">
            <h1 class="text-4xl font-bold text-indigo-800">M V M S P</h1>
        </div>

        <ul>
            <li class="mb-6 text-xl text-justify">I. <span class="underline font-semibold text-gray-800">MANDATE</span>
                <p class="mt-2 text-gray-700 leading-relaxed">&emsp;&emsp;{{ $officeMvmsp->mandate }}</p>
            </li>
            <li class="mb-6 text-xl text-justify">II. <span class="underline font-semibold text-gray-800">VISION</span>
                <p class="mt-2 text-gray-700 leading-relaxed">&emsp;&emsp;{{ $officeMvmsp->vision }}</p>
            </li>
            <li class="mb-6 text-xl text-justify">III. <span class="underline font-semibold text-gray-800">MISSION</span>
                <p class="mt-2 text-gray-700 leading-relaxed">&emsp;&emsp;{{ $officeMvmsp->mission }}</p>
            </li>
            <li class="mb-6 text-xl text-justify">IV. <span class="underline font-semibold text-gray-800">SERVICE PLEDGE</span>
                <p class="mt-2 text-gray-700 leading-relaxed">&emsp;&emsp;{{ $officeMvmsp->service_pledge }}</p>
            </li>

            <!-- ABANTE Acronym with Vertical Alignment -->
            <div class="mt-8 text-left">
                <div class="acronym-item">
                    <span class="acronym-letter text-indigo-700">A</span>
                    <span class="acronym-description">ccess to enhanced</span>
                </div>
                <div class="acronym-item">
                    <span class="acronym-letter text-indigo-700">B</span>
                    <span class="acronym-description">asic services (Health, Education, Social Welfare & Protective Services) and other services</span>
                </div>
                <div class="acronym-item">
                    <span class="acronym-letter text-indigo-700">A</span>
                    <span class="acronym-description">griculture & Fisheries</span>
                </div>
                <div class="acronym-item">
                    <span class="acronym-letter text-indigo-700">N</span>
                    <span class="acronym-description">frastructure</span>
                </div>
                <div class="acronym-item">
                    <span class="acronym-letter text-indigo-700">T</span>
                    <span class="acronym-description">ourism and Culture</span>
                </div>
                <div class="acronym-item">
                    <span class="acronym-letter text-indigo-700">E</span>
                    <span class="acronym-description">nvironment Management & Economic Development</span>
                </div>
                <div class="town-name text-center mt-8">
                    T U B I G O N
                </div>
                <div class="slogan text-center">
                    “Onward ever, backward never”
                </div>
            </div>
        </ul>
    </div>

    <style>
        .acronym-item {
            display: flex;
            align-items: baseline;
            margin-bottom: 10px;
        }
        .acronym-letter {
            font-size: 2rem;
            font-weight: bold;
            color: #4A1D73;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
            margin-right: 5px;
        }
        .acronym-description {
            font-size: 1.2rem;
            color: #333;
        }
        .town-name {
            font-size: 2rem;
            font-weight: bold;
            color: #243F8A;
            letter-spacing: 10px;
            margin-top: 20px;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        .slogan {
            color: green;
            font-style: italic;
            font-weight: bold;
            margin-top: 10px;
            text-align: center;
        }
    </style>
@endsection
