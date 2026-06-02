@props([
  'nodes' => collect(),
  'level' => 0,
])

<ul class="list-unstyled {{ $level ? 'ps-3 ms-2 border-start' : 'ps-0' }}">
  @foreach($nodes as $node)
    @php
      $children = $node->childrenRecursive ?? collect();
      $hasChildren = $children->isNotEmpty();
    @endphp

    <li class="mb-2">
      <div class="d-flex align-items-center gap-2">
        @if($hasChildren)
          <button class="btn btn-sm btn-light border-0 p-0 px-2 toggle-branch"
                  type="button"
                  data-bs-toggle="collapse"
                  data-bs-target="#node-{{ $node->id }}"
                  aria-expanded="true"
                  aria-controls="node-{{ $node->id }}">
            <i class="fa-solid fa-chevron-down small"></i>
          </button>
        @else
          <span class="d-inline-block" style="width:26px"></span>
        @endif

        @if(!empty($node->icon))
          <i class="{{ $node->icon }}"></i>
        @endif

        <div class="d-flex">
    
        <span class="fw-semibold">
            {{ $node->order }}. {{ $node->title }}
        </span>

        &nbsp; | &nbsp;

        <small class="text-muted">
            @if(!empty($node->route))
                {{ $node->route }}
            @endif

            @if(!empty($node->permission_name))
                • {{ $node->permission_name }}
            @endif
        </small>

      </div>

        <div class="ms-auto d-flex align-items-center gap-1">
          <a class="btn btn-outline-secondary btn-sm" href="{{ route('menus.show', $node->id) }}">
            <i class="fa-solid fa-list"></i>
          </a>
          @can('menu-edit')
            <a class="btn btn-outline-primary btn-sm" href="{{ route('menus.edit', $node->id) }}">
              <i class="fa-solid fa-pen-to-square"></i>
            </a>
          @endcan
          @can('menu-delete')
            @if($node->children->isEmpty())
                <button type="button"
                class="btn btn-outline-danger btn-sm btn-open-delete"
                data-url="{{ route('menus.destroy', $node->id) }}"
                data-name="{{ $node->title }}"
                data-bs-toggle="modal"
                data-bs-target="#confirmDeleteModal">
                <i class="fa-solid fa-trash"></i>
                </button>
            @else
                <button type="button"
                class="btn btn-outline-secondary btn-sm"
                disabled
                title="Menu ini memiliki sub-menu dan tidak bisa dihapus">
                <i class="fa-solid fa-ban"></i>
                </button>
            @endif
          @endcan
        </div>
      </div>

      @if($hasChildren)
        <div id="node-{{ $node->id }}" class="collapse show mt-2">
          <x-menus.tree :nodes="$children" :level="$level+1" />
        </div>
      @endif
    </li>
  @endforeach
</ul>
