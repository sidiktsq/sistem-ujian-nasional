@extends('layouts.app')
@section('page-title', 'Tambah Banyak Soal')

@section('content')
<div style="max-width:1000px">
    <a href="{{ route('guru.exams.questions.index', $exam) }}" class="btn btn-secondary btn-sm" style="margin-bottom:20px">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Soal
    </a>

    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px">
            <h3 style="font-size:18px; font-weight:700">Tambah Banyak Soal Sekaligus</h3>
            <div style="display:flex; gap:10px">
                <button type="button" onclick="addQuestion()" class="btn btn-success btn-sm">
                    <i class="fas fa-plus"></i> Tambah Soal
                </button>
                <button type="button" onclick="clearAll()" class="btn btn-secondary btn-sm">
                    <i class="fas fa-trash"></i> Hapus Semua
                </button>
            </div>
        </div>

        <form method="POST" action="{{ route('guru.exams.questions.bulk-store', $exam) }}" id="bulkForm">
            @csrf
            
            <div id="questions_container">
                <!-- Template soal akan ditambahkan di sini -->
            </div>

            <div style="display:flex; gap:10px; margin-top:32px; padding-top:20px; border-top:1px solid var(--border)">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Semua Soal
                </button>
                <a href="{{ route('guru.exams.questions.index', $exam) }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<script>
let questionCount = 0;

// Template untuk soal pilihan ganda
const multipleChoiceTemplate = (index) => `
    <div class="question-block" data-index="${index}" style="background:rgba(255,255,255,0.02); border:1px solid var(--border); border-radius:12px; padding:20px; margin-bottom:20px">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px">
            <h4 style="font-size:16px; font-weight:700; color:#818CF8">Soal #${index + 1}</h4>
            <button type="button" onclick="removeQuestion(${index})" class="btn btn-danger btn-sm">
                <i class="fas fa-times"></i> Hapus
            </button>
        </div>

        <div class="form-group">
            <label class="form-label">Tipe Soal <span style="color:#F87171">*</span></label>
            <select name="questions[${index}][question_type]" class="form-control" onchange="toggleOptions(${index})">
                <option value="multiple_choice">Pilihan Ganda</option>
                <option value="essay">Essay</option>
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Teks Pertanyaan <span style="color:#F87171">*</span></label>
            <textarea name="questions[${index}][question_text]" class="form-control" placeholder="Tuliskan pertanyaan di sini..." required></textarea>
        </div>

        <div class="form-group">
            <label class="form-label">Poin Soal <span style="color:#F87171">*</span></label>
            <input type="number" name="questions[${index}][points]" class="form-control" value="10" min="1" required>
        </div>

        <div class="options-container" id="options_${index}">
            <h5 style="font-size:14px; font-weight:700; margin:16px 0 12px">Pilihan Jawaban</h5>
            
            <div class="form-group" style="background:rgba(255,255,255,0.02); padding:12px; border-radius:8px; border:1px solid var(--border); margin-bottom:8px">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px">
                    <label style="font-size:12px; font-weight:600">Pilihan A</label>
                    <div style="display:flex; align-items:center; gap:6px">
                        <input type="radio" name="questions[${index}][correct_option]" value="0" checked>
                        <label style="font-size:11px; cursor:pointer; color:#34D399">Benar</label>
                    </div>
                </div>
                <input type="text" name="questions[${index}][options][]" class="form-control" placeholder="Masukkan pilihan A..." required>
            </div>

            <div class="form-group" style="background:rgba(255,255,255,0.02); padding:12px; border-radius:8px; border:1px solid var(--border); margin-bottom:8px">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px">
                    <label style="font-size:12px; font-weight:600">Pilihan B</label>
                    <div style="display:flex; align-items:center; gap:6px">
                        <input type="radio" name="questions[${index}][correct_option]" value="1">
                        <label style="font-size:11px; cursor:pointer; color:#34D399">Benar</label>
                    </div>
                </div>
                <input type="text" name="questions[${index}][options][]" class="form-control" placeholder="Masukkan pilihan B..." required>
            </div>

            <div class="form-group" style="background:rgba(255,255,255,0.02); padding:12px; border-radius:8px; border:1px solid var(--border); margin-bottom:8px">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px">
                    <label style="font-size:12px; font-weight:600">Pilihan C</label>
                    <div style="display:flex; align-items:center; gap:6px">
                        <input type="radio" name="questions[${index}][correct_option]" value="2">
                        <label style="font-size:11px; cursor:pointer; color:#34D399">Benar</label>
                    </div>
                </div>
                <input type="text" name="questions[${index}][options][]" class="form-control" placeholder="Masukkan pilihan C..." required>
            </div>

            <div class="form-group" style="background:rgba(255,255,255,0.02); padding:12px; border-radius:8px; border:1px solid var(--border)">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px">
                    <label style="font-size:12px; font-weight:600">Pilihan D</label>
                    <div style="display:flex; align-items:center; gap:6px">
                        <input type="radio" name="questions[${index}][correct_option]" value="3">
                        <label style="font-size:11px; cursor:pointer; color:#34D399">Benar</label>
                    </div>
                </div>
                <input type="text" name="questions[${index}][options][]" class="form-control" placeholder="Masukkan pilihan D..." required>
            </div>
        </div>
    </div>
`;

function addQuestion() {
    const container = document.getElementById('questions_container');
    const questionHtml = multipleChoiceTemplate(questionCount);
    container.insertAdjacentHTML('beforeend', questionHtml);
    questionCount++;
}

function removeQuestion(index) {
    const questionBlock = document.querySelector(`[data-index="${index}"]`);
    if (questionBlock) {
        questionBlock.remove();
        updateQuestionNumbers();
    }
}

function updateQuestionNumbers() {
    const questionBlocks = document.querySelectorAll('.question-block');
    questionBlocks.forEach((block, index) => {
        const title = block.querySelector('h4');
        if (title) {
            title.textContent = `Soal #${index + 1}`;
        }
        block.setAttribute('data-index', index);
    });
}

function toggleOptions(index) {
    const type = document.querySelector(`select[name="questions[${index}][question_type]"]`).value;
    const optionsContainer = document.getElementById(`options_${index}`);
    const optionInputs = optionsContainer.querySelectorAll('input');
    
    if (type === 'multiple_choice') {
        optionsContainer.style.display = 'block';
        optionInputs.forEach(input => input.disabled = false);
    } else {
        optionsContainer.style.display = 'none';
        optionInputs.forEach(input => input.disabled = true);
    }
}

function clearAll() {
    if (confirm('Apakah Anda yakin ingin menghapus semua soal?')) {
        document.getElementById('questions_container').innerHTML = '';
        questionCount = 0;
    }
}

// Tambahkan satu soal awal saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    addQuestion();
});
</script>
@endsection
