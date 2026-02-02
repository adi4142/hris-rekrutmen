<h2>Edit Job Applicant</h2>
<form action="{{ route('jobapplicant.update', $editjobApplicant->job_applicant_id) }}" method="POST" enctype="multipart/form-data">
    {{ csrf_field() }}
    @method('PUT')
    <div>
        <label for="name">Nama :</label>
        <br>
        <input type="text" name="name" value="{{ $editjobApplicant->name }}">
        @if ($errors->has('name'))
        <span class="text-danger">{{ $errors->first('name') }}</span>
        @endif
    </div>
    <div>
        <label for="email">Email :</label>
        <br>
        <input type="email" name="email" value="{{ $editjobApplicant->email }}">
        @if ($errors->has('email'))
        <span class="text-danger">{{ $errors->first('email') }}</span>
        @endif
    </div>
    <div>
        <label for="phone">Phone :</label>
        <br>
        <input type="number" name="phone" value="{{ $editjobApplicant->phone }}">
        @if ($errors->has('phone'))
        <span class="text-danger">{{ $errors->first('phone') }}</span>
        @endif
    </div>
    <div>
        <label for="address">Address :</label>
        <br>
        <input type="text" name="address" value="{{ $editjobApplicant->address }}">
        @if ($errors->has('address'))
        <span class="text-danger">{{ $errors->first('address') }}</span>
        @endif
    </div>
    <div>
        <label for="date_of_birth">Date of Birth :</label>
        <br>
        <input type="date" name="date_of_birth" value="{{ $editjobApplicant->date_of_birth }}">
        @if ($errors->has('date_of_birth'))
        <span class="text-danger">{{ $errors->first('date_of_birth') }}</span>
        @endif
    </div>
    <div>
        <label for="gender">Gender :</label>
        <br>
        <select name="gender" class="form-control">
            <option value="">--Pilih gender--</option>
            <option value="male" {{ $editjobApplicant->gender == 'male' ? 'selected' : '' }}>Male</option>
            <option value="female" {{ $editjobApplicant->gender == 'female' ? 'selected' : '' }}>Female</option>
        </select>
        @if ($errors->has('gender'))
        <span class="text-danger">{{ $errors->first('gender') }}</span>
        @endif
    </div>
    <div>
        <label for="cv">CV :</label>
        <br>
        <input type="file" name="cv_file">
        @if ($editjobApplicant->cv_file)
            <p>File sekarang: <a href="{{ asset('storage/' . $editjobApplicant->cv_file) }}" target="_blank">Lihat CV</a></p>
        @endif
        @if ($errors->has('cv_file'))
        <span class="text-danger">{{ $errors->first('cv_file') }}</span>
        @endif
    </div>
    <button type="submit">Simpan</button>
    <a href="{{ route('jobapplicant.index') }}">Kembali</a>
</form>