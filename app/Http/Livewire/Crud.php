<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Student;

class Crud extends Component
{

    use WithFileUploads;

    // public $showingPostModal = false;

    public $students, $nome, $email, $mobile, $imagem, $student_id;
    public $newImagem;
    public $oldImagem;
    public $isModalOpen = 0;

    // public function showPostModal()
    // {
    //     $this->showingPostModal = true;
    // }

    public function render()
    {
        $this->students = Student::all();
        return view('livewire.crud');
    }
    public function create()
    {
        $this->resetCreateForm();
        $this->openModalPopover();
    }
    public function openModalPopover()
    {
        $this->isModalOpen = true;
    }
    public function closeModalPopover()
    {
        $this->resetCreateForm();
        $this->isModalOpen = false;
    }
    private function resetCreateForm(){
        $this->nome = '';
        $this->email = '';
        $this->mobile = '';
        $this->imagem = '';
        $this->oldImagem = '';
        $this->newImagem = '';
    }
    
    public function store()
    {
        $this->validate([
            'nome' => 'required',
            'email' => 'required',
            'mobile' => 'required',
            'newImagem' => 'required | mimes:png,jpeg,jpg | max:1000', // 1MB Max
        ]);
        
        $imagem = $this->newImagem->store('public/students');
        
        Student::updateOrCreate(['id' => $this->student_id], [
            'nome' => $this->nome,
            'email' => $this->email,
            'mobile' => $this->mobile,
            'imagem' => $imagem,
        ]);
        session()->flash('message', $this->student_id ? 'Student updated.' : 'Student created.');
        $this->closeModalPopover();
        $this->resetCreateForm();
    }

    public function edit($id)
    {
        $student = Student::findOrFail($id);
        $this->student_id = $id;
        $this->nome = $student->nome;
        $this->email = $student->email;
        $this->mobile = $student->mobile;
        $this->imagem = '';
        $this->oldImagem = $student->imagem;
    
        $this->openModalPopover();
    }
    
    public function delete($id)
    {
        Student::find($id)->delete();
        session()->flash('message', 'Studen deleted.');
    }    
}
