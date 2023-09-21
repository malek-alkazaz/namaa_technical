{{-- <x-mail::message>
# Introduction

The body of your message.

<x-mail::button :url="''">
Button Text
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message> --}}




@component('mail::message')
# New Article Awaiting Review

Hello Admin,

A new article is awaiting review.

Article Title: {{ $article->title }}

Article Link: <a href="{{ url("/articles/$article->title") }}">Show article</a>

Thank you,
Namaa
@endcomponent
