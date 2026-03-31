<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\PointHistory;

class MemberController extends Controller
{
    public function index()
    {
        $members = Member::latest()->paginate(10);
        return Inertia::render('Member/Index', [
            'members' => $members,
        ]);
    }

    public function create()
    {
        return Inertia::render('Member/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:255',
            'telepon'  => 'nullable|string|max:20',
            'email'    => 'nullable|email|max:255',
            'alamat'   => 'nullable|string',
            'status'   => 'required|in:aktif,nonaktif',
        ]);

        Member::create($request->all());

        return redirect()->route('members.index')
            ->with('success', 'Member berhasil ditambahkan!');
    }

    public function edit(Member $member)
    {
        return Inertia::render('Member/Edit', [
            'member' => $member,
        ]);
    }

    public function update(Request $request, Member $member)
    {
        $request->validate([
            'nama'     => 'required|string|max:255',
            'telepon'  => 'nullable|string|max:20',
            'email'    => 'nullable|email|max:255',
            'alamat'   => 'nullable|string',
            'status'   => 'required|in:aktif,nonaktif',
        ]);

        $member->update($request->all());

        return redirect()->route('members.index')
            ->with('success', 'Member berhasil diupdate!');
    }

    public function destroy(Member $member)
    {
        $member->delete();
        return redirect()->route('members.index')
            ->with('success', 'Member berhasil dihapus!');
    }

    public function poinHistory(\App\Models\Member $member)
{
    return Inertia::render('Members/Poin', [
        'member'    => $member,
        'histories' => PointHistory::with('transaction')
            ->where('member_id', $member->id)
            ->latest()
            ->paginate(20),
    ]);
}

}