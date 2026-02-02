<h2>Edit Karyawan</h2>
<form action="{{ route('employee.update', $employee->nip) }}" method="POST">
    {{ csrf_field() }}
    @method('PUT')
    
    <div>
        <label for="nip">NIP :</label>
        <br>
        <input type="text" name="nip" value="{{ $employee->nip }}" required>
    </div>

    <div>
        <label for="name">Nama Karyawan :</label>
        <br>
        <input type="text" name="name" value="{{ $employee->name }}" required>
    </div>

    <div>
        <label for="user_id">Nama Akun :</label>
        <br>
        <select name="user_id" required>
            <option value="">-- Pilih Akun --</option>
            @foreach($user as $u)
                <option value="{{ $u->user_id }}" {{ $employee->user_id == $u->user_id ? 'selected' : '' }}>{{ $u->name }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="email">Email :</label>
        <br>
        <input type="email" name="email" value="{{ $employee->email }}" required>
    </div>

    <div>
        <label for="phone">Nomor Telpon :</label>
        <br>
        <input type="text" name="phone" value="{{ $employee->phone }}" required>
    </div>

    <div>
        <label for="departement_id">Departemen :</label>
        <br>
        <select name="departement_id" required>
            <option value="">-- Pilih Departemen --</option>
            @foreach($departements as $d)
                <option value="{{ $d->departement_id }}" {{ $employee->departement_id == $d->departement_id ? 'selected' : '' }}>{{ $d->name }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="position_id">Posisi :</label>
        <br>
        <select name="position_id" required>
            <option value="">-- Pilih Posisi --</option>
            @foreach($positions as $p)
                <option value="{{ $p->position_id }}" {{ $employee->position_id == $p->position_id ? 'selected' : '' }}>{{ $p->name }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="division_id">Divisi :</label>
        <br>
        <select name="division_id" required>
            <option value="">-- Pilih Divisi --</option>
            @foreach($divisions as $div)
                <option value="{{ $div->division_id }}" {{ $employee->division_id == $div->division_id ? 'selected' : '' }}>{{ $div->name }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="address">Alamat :</label>
        <br>
        <textarea name="address" required>{{ $employee->address }}</textarea>
    </div>

    <div>
        <label for="date_of_birth">Tanggal Lahir :</label>
        <br>
        <input type="date" name="date_of_birth" value="{{ $employee->date_of_birth }}" required>
    </div>

    <div>
        <label for="gender">Jenis Kelamin :</label>
        <br>
        <select name="gender" required>
            <option value="">-- Pilih Jenis Kelamin --</option>
            <option value="Male" {{ $employee->gender == 'Male' ? 'selected' : '' }}>Laki-laki (Male)</option>
            <option value="Female" {{ $employee->gender == 'Female' ? 'selected' : '' }}>Perempuan (Female)</option>
        </select>
    </div>

    <br>
    <button type="submit">Simpan</button>
    <a href="{{ route('employee.index') }}">Kembali</a>
</form>