<title>{{ MetaTag::get('title') }}</title>

{!! MetaTag::tag('description') !!}
{!! MetaTag::tag('image') !!}

{!! MetaTag::openGraph() !!}

{!! MetaTag::twitterCard() !!}