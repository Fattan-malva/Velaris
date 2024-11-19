<div>
    @if (session()->has('message'))
        <div style="color: green;">{{ session('message') }}</div>
    @endif

    <form wire:submit.prevent="createTicket" class="d-flex align-items-center">
        <div class="mb-3 me-2 flex-grow-1">
            <!-- Label for Subject -->
            <label for="subject">Subject:</label>
            
            <!-- Text input for subject with predefined options and ability to type freely -->
            <input 
                type="text" 
                wire:model="subject" 
                id="subject" 
                class="form-control" 
                placeholder="Enter or select a subject" 
                list="subject-options"
                required
                autocomplete="off" 
                onfocus="this.setAttribute('size', this.value.length)"
                onblur="this.removeAttribute('size')"
            />

            <!-- Dropdown for predefined options (visible only when typing) -->
            <datalist id="subject-options">
                <option value="Request Asset">
                <option value="Technical Issue">
                <option value="Maintenance">
                <option value="Other">
            </datalist>

            <!-- Show error messages -->
            @error('subject')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <!-- Submit Button -->
        <button class="btn btn-success" type="submit">Submit</button>
    </form>
</div>
