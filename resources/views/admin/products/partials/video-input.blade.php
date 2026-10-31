{{-- حقل رفع فيديو المنتج: يسمح باختيار فيديو من معرض الجوال أو التصوير مباشرة بالكاميرا --}}
@php($currentVideo = isset($product) ? $product->video : null)

@if($currentVideo)
    <div class="mb-3">
        <label class="form-label">الفيديو الحالي</label>
        <video src="{{ asset('storage/' . $currentVideo) }}" controls playsinline preload="metadata"
               class="rounded border w-100" style="max-height: 250px;"></video>
        <div class="form-check mt-2">
            <input class="form-check-input" type="checkbox" name="remove_video" value="1" id="remove_video">
            <label class="form-check-label text-danger" for="remove_video">حذف الفيديو الحالي</label>
        </div>
    </div>
@endif

<div class="mb-4">
    <label for="video" class="form-label">
        {{ $currentVideo ? 'فيديو جديد (اختياري)' : 'فيديو المنتج (اختياري)' }}
    </label>

    <div class="d-flex flex-wrap gap-2 mb-2">
        <button type="button" class="btn btn-outline-primary" id="pickVideoBtn">
            <i class="fas fa-photo-video me-1"></i> اختيار فيديو من الجوال
        </button>
        <button type="button" class="btn btn-outline-danger" id="recordVideoBtn">
            <i class="fas fa-video me-1"></i> تصوير فيديو الآن
        </button>
    </div>

    <input type="file"
           class="form-control @error('video') is-invalid @enderror"
           id="video"
           name="video"
           accept="video/*">
    <small class="form-text text-muted">
        من الجوال: اختر فيديو من المعرض أو صوّر مباشرة (MP4, MOV, WEBM, 3GP - الحد الأقصى: 50MB)
    </small>
    @error('video')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror

    <div id="videoPreview" class="mt-2" style="display: none;">
        <video controls playsinline class="rounded border w-100" style="max-height: 250px;"></video>
        <small class="text-muted d-block mt-1" id="videoInfo"></small>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('video');
    const pickBtn = document.getElementById('pickVideoBtn');
    const recordBtn = document.getElementById('recordVideoBtn');
    const previewBox = document.getElementById('videoPreview');
    const previewVideo = previewBox.querySelector('video');
    const info = document.getElementById('videoInfo');
    const MAX_BYTES = 50 * 1024 * 1024;
    let currentUrl = null;

    // اختيار من المعرض/الملفات
    pickBtn.addEventListener('click', function () {
        input.removeAttribute('capture');
        input.click();
    });

    // فتح كاميرا الجوال للتصوير مباشرة
    recordBtn.addEventListener('click', function () {
        input.setAttribute('capture', 'environment');
        input.click();
    });

    input.addEventListener('change', function () {
        const file = input.files[0];
        if (currentUrl) { URL.revokeObjectURL(currentUrl); currentUrl = null; }

        if (!file) { previewBox.style.display = 'none'; return; }

        if (file.size > MAX_BYTES) {
            alert('حجم الفيديو أكبر من 50MB، الرجاء اختيار فيديو أصغر.');
            input.value = '';
            previewBox.style.display = 'none';
            return;
        }

        currentUrl = URL.createObjectURL(file);
        previewVideo.src = currentUrl;
        info.textContent = file.name + ' - ' + (file.size / (1024 * 1024)).toFixed(1) + ' MB';
        previewBox.style.display = 'block';
    });
});
</script>
