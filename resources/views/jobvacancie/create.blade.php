<h2>Create Job Vacancie</h2>
<form action="{{ route('jobvacancie.store') }}" method="POST">
    {{ csrf_field() }}
    <label for="title">Title</label>
    <input type="text" name="title" id="title">
    <br>
    <label for="departement_id">Departement</label>
    <select name="departement_id" id="departement_id">
        <option value="">Select Departement</option>
        @foreach($departements as $departement)
            <option value="{{ $departement->departement_id }}">{{ $departement->name }}</option>
        @endforeach
    </select>
    <br>
    <label for="position_id">Position</label>
    <select name="position_id" id="position_id">
        <option value="">Select Position</option>
        @foreach($positions as $position)
            <option value="{{ $position->position_id }}">{{ $position->name }}</option>
        @endforeach
    </select>
    <br>
    <label for="description">Description</label>
    <input type="text" name="description" id="description">
    <br>
    <label for="requirements">Requirements</label>
    <input type="text" name="requirements" id="requirements">
    <br>
    <label for="status">Status</label>
    <select name="status" id="status">
        <option value="">Select Status</option>
        <option value="open">Open</option>
        <option value="closed">Closed</option>
    </select>
    <br>
    <button type="submit">Create</button>
</form>