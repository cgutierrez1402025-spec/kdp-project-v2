<div class="space-y-2">
    @foreach($versions as $version)
        <div class="flex items-center gap-2" style="margin-left: {{ $version['level'] * 24 }}px">
            <div class="w-2 h-2 rounded-full bg-{{ $version['status'] === 'published' ? 'green' : ($version['status'] === 'final' ? 'blue' : ($version['status'] === 'review' ? 'yellow' : 'gray') }}-500"></div>
            <span class="font-medium">v{{ $version['version_number'] }}</span>
            @if($version['is_published'])
                <span class="text-xs bg-green-100 text-green-800 px-2 py-0.5 rounded">Publicada</span>
            @elseif($version['is_final'])
                <span class="text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded">Final</span>
            @endif
            <span class="text-sm text-gray-500">{{ $version['created_at']->format('d/m/Y') }}</span>
        </div>
        @if(!empty($version['children']))
            @include('filament.widgets.version-tree-recursive', ['children' => $version['children']])
        @endif
    @endforeach
</div>