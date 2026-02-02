<h2>Job Vacancie</h2>
<table border="1">
    <thead>
        <tr>
            <th>No</th>
            <th>Title</th>
            <th>Departement</th>
            <th>Position</th>
            <th>Description</th>
            <th>Requirements</th>
            <th>Status</th>
            <th>
                <a href="{{ route('jobvacancie.create') }}">Tambah</a>
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach($jobVacancies as $jobVacancie)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $jobVacancie->title }}</td>
            <td>{{ $jobVacancie->departement->name }}</td>
            <td>{{ $jobVacancie->position->name }}</td>
            <td>{{ $jobVacancie->description }}</td>
            <td>{{ $jobVacancie->requirements }}</td>
            <td>{{ $jobVacancie->status }}</td>
            <td>
                <form action="{{ route('jobvacancie.destroy', $jobVacancie->vacancies_id) }}" method="POST" style="display:inline;">
                    {{ csrf_field() }}
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Kamu serius?')">Hapus</button>
                    <a href="{{ route('jobvacancie.edit', $jobVacancie->vacancies_id) }}">Edit</a>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>