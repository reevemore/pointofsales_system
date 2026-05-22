<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HasTokoScope;
use App\Models\Member;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MemberController extends Controller
{
    use HasTokoScope;

    public function index()
    {
        session(['pageTitle' => 'Member', 'pageSubtitle' => 'Daftar member']);

        $members = $this->scopedQuery(Member::query())
            ->latest()
            ->get();

        return Inertia::render('Member/Index', [
            'members' => $members,
        ]);
    }

    public function create()
    {
        session(['pageTitle' => 'Tambah Member', 'pageSubtitle' => '']);

        return Inertia::render('Member/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'    => 'required|string|max:255',
            'email'   => 'nullable|email|max:255',
            'telepon' => 'nullable|string|max:20',
            'status'  => 'boolean',
        ]);

        Member::create([
            'toko_id' => $this->tokoId(),
            'nama'    => $request->nama,
            'email'   => $request->email,
            'telepon' => $request->telepon,
            'poin'    => 0,
            'status'  => $request->status ?? true,
        ]);

        return redirect()->route('members.index')
            ->with('success', 'Member berhasil ditambahkan.');
    }

    public function edit(Member $member)
    {
        abort_if($member->toko_id !== $this->tokoId(), 403);

        session(['pageTitle' => 'Edit Member', 'pageSubtitle' => $member->nama]);

        return Inertia::render('Member/Edit', [
            'member' => $member,
        ]);
    }

    public function update(Request $request, Member $member)
    {
        abort_if($member->toko_id !== $this->tokoId(), 403);

        $request->validate([
            'nama'    => 'required|string|max:255',
            'email'   => 'nullable|email|max:255',
            'telepon' => 'nullable|string|max:20',
            'status'  => 'boolean',
        ]);

        $member->update([
            'nama'    => $request->nama,
            'email'   => $request->email,
            'telepon' => $request->telepon,
            'status'  => $request->status ?? true,
        ]);

        return redirect()->route('members.index')
            ->with('success', 'Member berhasil diperbarui.');
    }

    public function destroy(Member $member)
    {
        abort_if($member->toko_id !== $this->tokoId(), 403);

        $member->delete();

        return redirect()->route('members.index')
            ->with('success', 'Member berhasil dihapus.');
    }

    public function poinHistory(Member $member)
    {
        abort_if($member->toko_id !== $this->tokoId(), 403);

        session(['pageTitle' => 'Riwayat Poin', 'pageSubtitle' => $member->nama]);

        $histories = $member->pointHistories()
            ->with('transaction')
            ->latest()
            ->get();

        return Inertia::render('Members/Poin', [
            'member'    => $member,
            'histories' => $histories,
        ]);
    }
}