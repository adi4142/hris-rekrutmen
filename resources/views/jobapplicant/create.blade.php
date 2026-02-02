@extends('layouts.app')
@section('title', 'Job Applicant')
@section('page_title', 'Manajemen Pendaftar')

@section('content')

<h2>Form Lamaran</h2>
<form action="{{ route('jobapplicant.store') }}" method="POST" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="row">
    <div class="col-md-6 form-group">
        <label for="name">Nama :</label>
        <br>
        <input type="text" name="name" value="{{ old('name') }}">
        @if ($errors->has('name'))
        <span class="text-danger">{{ $errors->first('name') }}</span>
        @endif
    </div>
    <div class="col-md-6 form-group">
        <label for="email">Email :</label>
        <br>
        <input type="email" name="email" value="{{ old('email') }}">
        @if ($errors->has('email'))
        <span class="text-danger">{{ $errors->first('email') }}</span>
        @endif
    </div>
    <div class="col-md-6 form-group">
        <label for="phone">Phone :</label>
        <br>
        <input type="number" name="phone" value="{{ old('phone') }}">
        @if ($errors->has('phone'))
        <span class="text-danger">{{ $errors->first('phone') }}</span>
        @endif
    </div>
    <div class="col-md-6 form-group">
        <label for="address">Address :</label>
        <br>
        <input type="text" name="address" value="{{ old('address') }}">
        @if ($errors->has('address'))
        <span class="text-danger">{{ $errors->first('address') }}</span>
        @endif
    </div>
    <div class="col-md-6 form-group">
        <label for="date_of_birth">Date of Birth :</label>
        <br>
        <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}">
        @if ($errors->has('date_of_birth'))
        <span class="text-danger">{{ $errors->first('date_of_birth') }}</span>
        @endif
    </div>
    <div class="col-md-6 form-group">
        <label for="gender">Gender :</label>
        <br>
        <select name="gender" class="form-control">
            <option value="" selected>--Pilih gender--</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
        </select>
        @if ($errors->has('gender'))
        <span class="text-danger">{{ $errors->first('gender') }}</span>
        @endif
    </div>
    <div class="col-md-6 form-group">
        <label for="vacancies_id">Lowongan :</label>
        <br>
        <select name="vacancies_id" class="form-control">
            <option value="" selected>--Pilih Lowongan--</option>
            @foreach($jobVacancies as $vacancy)
                <option value="{{ $vacancy->vacancies_id }}">{{ $vacancy->title }}</option>
            @endforeach
        </select>
        @if ($errors->has('vacancies_id'))
        <span class="text-danger">{{ $errors->first('vacancies_id') }}</span>
        @endif
    </div>
    <div class="col-md-6 form-group">
        <label for="cv_file">CV :</label>
        <br>
        <input type="file" name="cv_file">
        @if ($errors->has('cv_file'))
        <span class="text-danger">{{ $errors->first('cv_file') }}</span>
        @endif
    </div>
    </div>
    <button type="submit">Simpan</button>
    <a href="{{ route('jobapplicant.index') }}">Kembali</a>
</form>
@endsection