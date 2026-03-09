@extends('layouts.app')

@section('page-title', 'Pengaturan Akun')

@section('content')
<div style="max-width: 800px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h2 style="font-size: 24px; font-weight: 700;">Pengaturan Akun</h2>
    </div>

    {{-- Profile Picture Upload --}}
    <div class="card" style="margin-bottom: 24px; overflow: hidden;">
        <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 20px; border-bottom: 1px solid var(--border); padding-bottom: 12px;">
            <i class="fas fa-image" style="color: var(--murid-primary); margin-right: 8px;"></i> Foto Profil
        </h3>
        
        <div style="display: grid; grid-template-columns: 140px 1fr; gap: 32px; align-items: start;">
            {{-- Left Column: Avatar Display --}}
            <div style="display: flex; flex-direction: column; align-items: center; gap: 12px;">
                <div style="position: relative; width: 140px; height: 140px; border-radius: 50%; padding: 4px; border: 2px dashed var(--border); background: rgba(255,255,255,0.02); display: flex; align-items: center; justify-content: center;">
                    @if(auth()->user()->avatar)
                        <img src="{{ auth()->user()->avatar }}?t={{ time() }}" alt="Profile" id="current-avatar" 
                             style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover; border: 4px solid var(--card);">
                    @else
                        <div id="current-avatar" style="width: 100%; height: 100%; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 48px; font-weight: 800; border: 4px solid var(--card);
                            background: linear-gradient(135deg, #34D399, #059669); 
                            color: #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.2);">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    @endif
                    <div id="preview-container" style="position: absolute; inset: 4px; border-radius: 50%; overflow: hidden; display: none; border: 4px solid var(--murid-primary); z-index: 10; background: var(--card); align-items: center; justify-content: center;"></div>
                </div>
                
                @if(auth()->user()->avatar && !str_contains(auth()->user()->avatar, 'google'))
                <button type="button" class="btn btn-danger btn-sm" style="width: 100%; padding: 6px; font-size: 11px;" onclick="if(confirm('Hapus foto profil?')) document.getElementById('remove-avatar-form').submit()">
                    <i class="fas fa-trash-alt"></i> Hapus Foto
                </button>
                @endif
            </div>

            {{-- Right Column: Upload Controls --}}
            <div style="display: flex; flex-direction: column; gap: 20px; padding-top: 8px;">
                <div>
                    <h4 style="font-size: 14px; font-weight: 600; margin-bottom: 4px; color: var(--text);">Upload Foto Baru</h4>
                    <p style="font-size: 12px; color: var(--text-muted); line-height: 1.5;">Pilih foto formal untuk profil Anda. Foto ini akan muncul di dashboard dan laporan ujian.</p>
                </div>

                <form method="POST" action="{{ route('murid.profile.upload-avatar') }}" enctype="multipart/form-data" onsubmit="return validateAvatarForm()">
                    @csrf
                    <div style="display: flex; flex-direction: column; gap: 16px;">
                        <div style="background: var(--dark); border: 1px solid var(--border); border-radius: 12px; padding: 12px; display: flex; align-items: center; gap: 12px;">
                            <label for="avatar" class="btn btn-secondary btn-sm" style="margin: 0; white-space: nowrap; flex-shrink: 0;">
                                <i class="fas fa-folder-open"></i> Pilih File
                            </label>
                            <input type="file" id="avatar" name="avatar" accept="image/*" style="display: none;" onchange="previewImage(event)">
                            <span id="file-name" style="font-size: 12px; color: var(--text-muted); overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">Belum ada file dipilih</span>
                        </div>

                        <button type="submit" class="btn btn-green" style="width: fit-content; padding: 12px 24px; box-shadow: 0 4px 14px rgba(5,150,105,0.3);">
                            <i class="fas fa-cloud-upload-alt"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>

                @if(auth()->user()->avatar && !str_contains(auth()->user()->avatar, 'google'))
                <form id="remove-avatar-form" method="POST" action="{{ route('murid.profile.remove-avatar') }}" style="display: none;">
                    @csrf
                </form>
                @endif

                <div style="background: rgba(255,255,255,0.03); border-radius: 8px; padding: 10px 14px; display: flex; align-items: center; gap: 10px; width: fit-content;">
                    <i class="fas fa-info-circle" style="color: var(--yellow); font-size: 14px;"></i>
                    <span style="font-size: 11px; color: var(--text-muted);">Format: <strong>JPG, PNG, GIF</strong>. Ukuran maks: <strong>2MB</strong></span>
                </div>
            </div>
        </div>
    </div>

    {{-- Account Information --}}
    <div class="card" style="margin-bottom: 24px;">
        <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 20px;">Informasi Akun</h3>
        <form method="POST" action="{{ route('murid.profile.update') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="form-control" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Role</label>
                    <input type="text" value="{{ ucfirst(auth()->user()->role) }}" class="form-control" style="background: rgba(255,255,255,0.03) !important;" readonly>
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal Bergabung</label>
                    <input type="text" value="{{ auth()->user()->created_at->format('d M Y') }}" class="form-control" style="background: rgba(255,255,255,0.03) !important;" readonly>
                </div>
            </div>
            
            <button type="submit" class="btn btn-green">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
        </form>
    </div>

    {{-- Danger Zone --}}
    <div class="card" style="border-color: #EF4444;">
        <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 20px; color: #EF4444;">
            <i class="fas fa-exclamation-triangle"></i> Zona Berbahaya
        </h3>
        <div style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); border-radius: 8px; padding: 16px; margin-bottom: 16px;">
            <div style="font-weight: 600; margin-bottom: 8px; color: #EF4444;">⚠️ Hapus Akun?</div>
            <div style="font-size: 13px; color: var(--text-muted); line-height: 1.5;">
                Tindakan ini akan <strong>menghapus permanen</strong> akun Anda dan semua data yang terkait. Tindakan ini <strong>tidak dapat dibatalkan</strong>.
            </div>
        </div>
        
        <button type="button" class="btn btn-danger" onclick="confirmDeleteAccount()" style="background: #EF4444;">
            <i class="fas fa-trash-alt"></i> HAPUS ACCOUNT
        </button>
    </div>
</div>

{{-- Delete Account Modal --}}
<div id="deleteModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: var(--card); border-radius: 16px; padding: 24px; max-width: 400px; width: 90%;">
        <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 16px; color: #EF4444;">
            <i class="fas fa-exclamation-triangle"></i> Konfirmasi Hapus Akun
        </h3>
        <p style="margin-bottom: 20px; color: var(--text-muted);">
            Ketik <strong>"HAPUS"</strong> untuk mengkonfirmasi penghapusan akun Anda secara permanen:
        </p>
        <form method="POST" action="{{ route('murid.profile.delete') }}" onsubmit="return validateDelete()">
            @csrf
            @method('DELETE')
            <div class="form-group">
                <input type="text" name="confirm_text" class="form-control" placeholder="Ketik HAPUS" required>
            </div>
            <div style="display: flex; gap: 12px;">
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Ya, Hapus Akun
                </button>
                <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    const previewContainer = document.getElementById('preview-container');
    const fileName = document.getElementById('file-name');
    const currentAvatar = document.getElementById('current-avatar');
    
    if (file) {
        fileName.textContent = file.name;
        
        const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
        if (!validTypes.includes(file.type)) {
            alert('Format file tidak didukung. Gunakan JPG, PNG, atau GIF.');
            event.target.value = '';
            fileName.textContent = 'Belum ada file dipilih';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            previewContainer.innerHTML = `<img src="${e.target.result}" style="width: 100%; height: 100%; object-fit: cover;">`;
            previewContainer.style.display = 'flex';
            if (currentAvatar) currentAvatar.style.visibility = 'hidden';
        }
        reader.readAsDataURL(file);
    } else {
        fileName.textContent = 'Belum ada file dipilih';
        previewContainer.style.display = 'none';
        if (currentAvatar) currentAvatar.style.visibility = 'visible';
    }
}

function validateAvatarForm() {
    const fileInput = document.getElementById('avatar');
    if (!fileInput.files[0]) {
        alert('Silakan pilih file terlebih dahulu.');
        return false;
    }
    return true;
}

function confirmDeleteAccount() {
    document.getElementById('deleteModal').style.display = 'flex';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
}

function validateDelete() {
    const confirmInput = document.querySelector('input[name="confirm_text"]');
    if (confirmInput.value !== 'HAPUS') {
        alert('Mohon ketik HAPUS untuk mengkonfirmasi.');
        return false;
    }
    return true;
}
</script>
@endsection
