@extends('layouts.app')
@section('page-title', 'Tambah Soal Baru')

@section('content')
<div style="max-width:800px">
    <a href="{{ route('guru.exams.questions.index', $exam) }}" class="btn btn-secondary btn-sm" style="margin-bottom:20px">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Soal
    </a>

    <div class="card">
        <form method="POST" action="{{ route('guru.exams.questions.store', $exam) }}">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Tipe Soal <span style="color:#F87171">*</span></label>
                <select name="question_type" id="question_type" class="form-control" onchange="toggleOptions()">
                    <option value="multiple_choice" {{ old('question_type') == 'multiple_choice' ? 'selected' : '' }}>Pilihan Ganda</option>
                    <option value="essay" {{ old('question_type') == 'essay' ? 'selected' : '' }}>Essay</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Teks Pertanyaan <span style="color:#F87171">*</span></label>
                <textarea name="question_text" class="form-control" placeholder="Tuliskan pertanyaan di sini..." required>{{ old('question_text') }}</textarea>
                @error('question_text')<p class="error-text">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Poin Soal <span style="color:#F87171">*</span></label>
                <input type="number" name="points" class="form-control" value="{{ old('points', 10) }}" min="1" required>
                <p style="font-size:11px; color:var(--text-muted); margin-top:4px">Contoh: 10 poin per soal jika total ada 10 soal untuk mencapai skor 100.</p>
            </div>

            <div id="options_container">
                <h4 style="font-size:14px; font-weight:700; margin:24px 0 16px; display:flex; align-items:center; gap:8px">
                    <i class="fas fa-list-ul" style="color:#818CF8"></i> Pilihan Jawaban
                </h4>
                
                @for($i = 0; $i < 4; $i++)
                <div class="form-group" style="background:rgba(255,255,255,0.02); padding:16px; border-radius:12px; border:1px solid var(--border)">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px">
                        <label class="form-label" style="margin-bottom:0">Pilihan {{ chr(65 + $i) }}</label>
                        <div style="display:flex; align-items:center; gap:8px">
                            <input type="radio" name="correct_option" value="{{ $i }}" id="correct_{{ $i }}" {{ old('correct_option') == $i ? 'checked' : ($i == 0 ? 'checked' : '') }}>
                            <label for="correct_{{ $i }}" style="font-size:12px; cursor:pointer; font-weight:600; color:#34D399">Jawaban Benar</label>
                        </div>
                    </div>
                    <input type="text" name="options[]" class="form-control" placeholder="Masukkan teks pilihan..." value="{{ old('options.'.$i) }}">
                </div>
                @endfor
            </div>

            <div style="display:flex; gap:10px; margin-top:32px; padding-top:20px; border-top:1px solid var(--border)">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Soal</button>
                <a href="{{ route('guru.exams.questions.index', $exam) }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleOptions() {
        const type = document.getElementById('question_type').value;
        const container = document.getElementById('options_container');
        const optionInputs = container.querySelectorAll('input[name="options[]"], input[name="correct_option"]');
        
        if (type === 'multiple_choice') {
            container.style.display = 'block';
            optionInputs.forEach(input => input.disabled = false);
        } else {
            container.style.display = 'none';
            optionInputs.forEach(input => input.disabled = true);
        }
    }
    window.onload = toggleOptions;
</script>
@endsection
