<h2>Edit Job Vacancie</h2>
<form action="{{ route('jobvacancie.update', $editJobVacancie->vacancies_id) }}" method="POST">
    {{ csrf_field() }}
    @method('PUT')
    <label for="title">Title</label>
    <input type="text" name="title" id="title" value="{{ $editJobVacancie->title }}">
    <br>
    <label for="departement_id">Departement</label>
    <select name="departement_id" id="departement_id">
        @foreach($departement as $departement)
            <option value="{{ $departement->departement_id }}" {{ $editJobVacancie->departement_id == $departement->departement_id ? 'selected' : '' }}>{{ $departement->name }}</option>
        @endforeach
    </select>
    <br>
    <label for="position_id">Position</label>
    <select name="position_id" id="position_id">
        @foreach($position as $position)
            <option value="{{ $position->position_id }}" {{ $editJobVacancie->position_id == $position->position_id ? 'selected' : '' }}>{{ $position->name }}</option>
        @endforeach
    </select>
    <br>
    <label for="description">Description</label>
    <input type="text" name="description" id="description" value="{{ $editJobVacancie->description }}">
    <br>
    <label for="requirements">Requirements</label>
    <input type="text" name="requirements" id="requirements" value="{{ $editJobVacancie->requirements }}">
    <br>
    <label for="status">Status</label>
    <select name="status" id="status">
        <option value="open" {{ $editJobVacancie->status == 'open' ? 'selected' : '' }}>Open</option>
        <option value="closed" {{ $editJobVacancie->status == 'closed' ? 'selected' : '' }}>Closed</option>
    </select>
    <br>
    <button type="submit">Update</button>
</form>