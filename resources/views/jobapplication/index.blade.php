<table border="1">
    <thead>
        <tr>
            <th>Nama Pelamar</th>
            <th>Lowongan</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($jobapplications as $app)
        <tr>
            <td>{{ $app->jobApplicant->name }}</td>
            <td>{{ $app->jobVacancie->title }}</td>
            <td>{{ $app->status }}</td>
            <td>
                <form action="{{ route('jobapplication.update', $app->application_id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <select name="status">
                        <option value="pending" {{ $app->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ $app->status == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ $app->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                    <button type="submit">Update</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
