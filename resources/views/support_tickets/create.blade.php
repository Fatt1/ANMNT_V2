@extends('layouts.store', ['title' => 'Submit a Support Ticket'])

@section('content')
<div class="max-w-2xl mx-auto shop-card p-6 mt-10">
    <h2 class="text-3xl font-semibold mb-6">Submit a Support Ticket</h2>
    
    @if(session('success'))
        <div class="p-4 mb-4 text-green-700 bg-green-100 rounded">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Subject</label>
            <input type="text" name="subject" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm p-2 border" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Message</label>
            <textarea name="message" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm p-2 border" required></textarea>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700">Evidence File (Required)</label>
            
            <!-- File upload area -->
            <div id="uploadArea" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md cursor-pointer hover:bg-gray-50 transition">
                <div class="space-y-1 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <div class="flex text-sm text-gray-600">
                        <label for="evidence_file" class="relative cursor-pointer bg-white rounded-md font-medium text-brand-600 hover:text-brand-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-brand-500">
                            <span>Upload a file</span>
                            <input id="evidence_file" name="evidence_file" type="file" class="sr-only" required>
                        </label>
                        <p class="pl-1">or drag and drop</p>
                    </div>
                </div>
            </div>

            <!-- File preview -->
            <div id="filePreview" class="mt-4 hidden">
                <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <svg class="w-10 h-10 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.3A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z"></path>
                            </svg>
                            <div>
                                <p id="fileName" class="font-medium text-gray-900"></p>
                                <p id="fileSize" class="text-sm text-gray-600"></p>
                            </div>
                        </div>
                        <button type="button" id="removeBtn" class="text-red-600 hover:text-red-700 font-medium">Remove</button>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <button type="submit" class="shop-btn w-full">Submit Ticket</button>
        </div>
    </form>
</div>

<script>
    const fileInput = document.getElementById('evidence_file');
    const uploadArea = document.getElementById('uploadArea');
    const filePreview = document.getElementById('filePreview');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    const removeBtn = document.getElementById('removeBtn');

    // Hiển thị file khi chọn
    fileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            fileName.textContent = file.name;
            fileSize.textContent = (file.size / 1024).toFixed(2) + ' KB';
            
            uploadArea.classList.add('hidden');
            filePreview.classList.remove('hidden');
        }
    });

    // Xóa file
    removeBtn.addEventListener('click', function(e) {
        e.preventDefault();
        fileInput.value = '';
        filePreview.classList.add('hidden');
        uploadArea.classList.remove('hidden');
    });

    // Drag and drop
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadArea.classList.add('bg-gray-100');
    });

    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('bg-gray-100');
    });

    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('bg-gray-100');
        
        if (e.dataTransfer.files && e.dataTransfer.files[0]) {
            fileInput.files = e.dataTransfer.files;
            const event = new Event('change', { bubbles: true });
            fileInput.dispatchEvent(event);
        }
    });
</script>
@endsection
