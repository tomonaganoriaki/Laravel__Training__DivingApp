<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function index(): View
    {
        $news = News::all();
        return view('admin.news.index')->with('newss', $news);
    }

    public function create(): View
    {
        return view('admin.news.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => ['required', 'string', 'max:100'],
        ]);
        $news = News::create([
            'title' => $request->title,
            'content' => $request->content,
        ]);
        session()->flash('flash_message', 'ニュースを作成しました。');
        return redirect()->route('admin.news.index');
    }

    public function edit($id): View
    {
        $news = News::findOrFail($id);
        return view('admin.news.edit')->with('news', $news);
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $news = News::findOrFail($id);
        $request->validate([
            'title' => ['required', 'string', 'max:100'],
        ]);
        $news->update([
            'title' => $request->title,
            'content' => $request->content,
        ]);
        session()->flash('flash_message', 'ニュースを更新しました。');
        return redirect()->route('admin.news.index');
    }

    public function destroy($id): RedirectResponse
    {
        $news = News::findOrFail($id);
        $news->delete();
        session()->flash('flash_message', 'ニュースを削除しました。');
        return redirect()->route('admin.news.index');
    }
}
