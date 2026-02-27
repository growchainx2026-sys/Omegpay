@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
@php
$setting = \App\Helpers\Helper::settings();

$text = "Hoje";
if($periodo == 'mes') $text = "Mês";
elseif($periodo == 'semana') $text = "Semana";
elseif($periodo == 'tudo') $text = "Tudo";
@endphp

<div class="header mt-3 d-flex justify-content-between">
  <h1 class="header-title">
    Dashboard
  </h1>
  <form id="form-filter" action="{{ route('dashboard') }}" method="GET">
    <div>
      <select name="periodo" class="form-select w-auto" onchange="document.getElementById('form-filter').submit()" style="border-radius: 10px;">
        <option value="tudo" {{ $periodo == 'tudo' ? 'selected' : '' }}>Tudo</option>
        <option value="mes" {{ $periodo == 'mes' ? 'selected' : '' }}>Mês</option>
        <option value="semana" {{ $periodo == 'semana' ? 'selected' : '' }}>Semana</option>
        <option value="dia" {{ $periodo == 'dia' ? 'selected' : '' }}>Dia</option>
      </select>
    </div>
  </form>
</div>

<div class="row " style="z-index: 999; margin-top: -20px;">

  <!-- Swiper banners -->
  <div class="col-12 mb-3">
    <div class="swiper mySwiper">
      <div class="swiper-wrapper">
        @foreach($banners as $banner)
        <div class="swiper-slide">
          <img src="/storage/{{ $banner->image }}" class="img-fluid w-100" style="border-radius:10px;" alt="Imagem">
        </div>
        @endforeach
      </div>
      <div class="swiper-pagination"></div>
    </div>
  </div>
  
  <!-- Saldo Disponível -->
  <div class="col-md-4">
    <div class="card card-dash p-3 position-relative py-4">
      <div class="icon-eye position-absolute top-3 end-2 m-2" data-lucide="eye" style="cursor: pointer;" style="cursor: pointer;"></div>
      <div class="d-flex align-items-center gap-2 mb-1">
        <i data-lucide="wallet" class="text-muted"></i>
        <small class="text-muted">Saldo Disponível</small>
      </div>
      <h2 class="mb-0 valor-visivel" data-valor="R$ {{ number_format(auth()->user()->saldo, 2, ',', '.') }}">
        R$ {{ number_format(auth()->user()->saldo, 2, ',', '.') }}
      </h2>
    </div>
  </div>

  <!-- Transações Realizadas -->
  <div class="col-md-4">
    <div class="card card-dash p-3 position-relative py-4">
      <div class="icon-eye position-absolute top-3 end-2 m-2" data-lucide="eye" style="cursor: pointer;" style="cursor: pointer;"></div>
      <div class="d-flex align-items-center gap-2 mb-1">
        <i data-lucide="dollar-sign" class="text-muted"></i>
        <small class="text-muted">Transações Realizadas ({{ $text }})</small>
      </div>
      <h2 class="mb-0 valor-visivel" data-valor="R$ {{ number_format($transactionsIn->where('status', 'pago')->sum('amount'), 2, ',', '.') }}">
        R$ {{ number_format($transactionsIn->where('status', 'pago')->sum('amount'), 2, ',', '.') }}
      </h2>
    </div>
  </div>

  <!-- Quantidade de Transações -->
  <div class="col-md-4">
    <div class="card card-dash p-3 position-relative py-4">
      <div class="icon-eye position-absolute top-3 end-2 m-2" data-lucide="eye" style="cursor: pointer;" style="cursor: pointer;"></div>
      <div class="d-flex align-items-center gap-2 mb-1">
        <i data-lucide="trending-up" class="text-muted"></i>
        <small class="text-muted">Quantidade de Transações ({{ $text }})</small>
      </div>
      <h2 class="mb-0 valor-visivel" data-valor="{{ (clone $transactionsIn)->where('status', 'pago')->count() }}">
        {{ (clone $transactionsIn)->where('status', 'pago')->count() }}
      </h2>
    </div>
  </div>

  <!-- Ticket Médio -->
  <div class="col-md-4">
    <div class="card card-dash p-3 position-relative py-4">
      <div class="icon-eye position-absolute top-3 end-2 m-2" data-lucide="eye" style="cursor: pointer;" style="cursor: pointer;"></div>
      <div class="d-flex align-items-center gap-2 mb-1">
        <i data-lucide="credit-card" class="text-muted"></i>
        <small class="text-muted">Ticket Médio ({{ $text }})</small>
      </div>
      <h2 class="mb-0 valor-visivel" data-valor="R$ {{ number_format($transactionsIn->where('status', 'pago')->avg('amount'), 2, ',', '.') }}">
        R$ {{ number_format($transactionsIn->where('status', 'pago')->avg('amount'), 2, ',', '.') }}
      </h2>
    </div>
  </div>

  <!-- Saques -->
  <div class="col-md-4">
    <div class="card card-dash p-3 position-relative py-4">
      <div class="icon-eye position-absolute top-3 end-2 m-2" data-lucide="eye" style="cursor: pointer;" style="cursor: pointer;"></div>
      <div class="d-flex align-items-center gap-2 mb-1">
        <i data-lucide="credit-card" class="text-muted"></i>
        <small class="text-muted">Saques ({{ $text }})</small>
      </div>
      <h2 class="mb-0 valor-visivel" data-valor="R$ {{ number_format($transactionsOut->where('status', 'pago')->sum('amount'), 2, ',', '.') }}">
        R$ {{ number_format($transactionsOut->where('status', 'pago')->sum('amount'), 2, ',', '.') }}
      </h2>
    </div>
  </div>

  <!-- MEDs -->
  <div class="col-md-4">
    <div class="card card-dash p-3 position-relative py-4">
      <div class="icon-eye position-absolute top-3 end-2 m-2" data-lucide="eye" style="cursor: pointer;" style="cursor: pointer;"></div>
      <div class="d-flex align-items-center gap-2 mb-1">
        <i data-lucide="alert-triangle" class="text-muted"></i>
        <small class="text-muted">MEDs ({{ $text }})</small>
      </div>
      <h2 class="mb-0 valor-visivel" data-valor="R$ 0,00">R$ 0,00</h2>
    </div>
  </div>

  <div class="col-12 mt-3">
    <div class="card card-dash shadow-sm rounded-6">
      <div class="card-body">
        <h5 class="card-title fw-semibold" >Meios de Pagamento</h5>
        <div class="table-responsive">
          <table class="table align-middle mb-0">
            <thead>
              <tr>
                <th></th>
                <th class="text-center">Conversão</th>
                <th class="text-end"></th>
                <th class="text-end">Valor</th>
                <th class="text-end"></th>
              </tr>
            </thead>
            <tbody>
              @php
              $totalTransacoes = (clone $transactionsIn)->count();
              $totalPix = (clone $transactionsIn)->count();
              $pixPagos = (clone $transactionsIn)->where('status', 'pago')->count();

              $conversaoPix = $totalPix > 0 ? ($pixPagos / $totalPix) * 100 : 0;
              $conversaoPixGeral = $totalTransacoes > 0 ? ($pixPagos / $totalTransacoes) * 100 : 0;
              @endphp

             
 <tr>
                <td class="d-flex align-items-center gap-2">
                  <i data-lucide="qr-code" class="lucide text-muted" style="width:20px !important;"></i>
                  <span style="color: rgb(17 24 39) !important;font-weight:500;">PIX</span>
                </td>

                <td class="text-center">
                   <span class="value" data-value="{{ number_format($conversaoPix, 2, ',', '') }}%">
                    {{ number_format($conversaoPix, 2, ',', '') }}%
                  </span>
                </td>
                <td class="text-end">
                  <i data-lucide="eye" style="cursor: pointer;" class="lucide small-icon text-muted eye-toggle"></i>
                </td>
                <td class="text-end">
                  <span class="d-inline-flex align-items-center justify-content-end gap-1 toggle-cell" style="cursor: pointer">
                    <span class="value" style="color: black !important;font-weight:500;" data-value="R$ {{ number_format($transactionsIn->where('status', 'pago')->sum('amount'), 2, ',', '.') }}">
                      R$ {{ number_format($transactionsIn->where('status', 'pago')->sum('amount'), 2, ',', '.') }}
                    </span>
                  </span>
                </td>
                <td class="text-end">
                  <i data-lucide="eye" style="cursor: pointer;" class="lucide small-icon text-muted eye-toggle"></i>
                </td>
              </tr>

              <tr>
                <td class="d-flex align-items-center gap-2">
                  <i data-lucide="file-text" class="lucide text-muted" style="width:20px !important;"></i>
                  <span style="color: rgb(17 24 39) !important;font-weight:500;">Boleto</span>
                </td>

                <td class="text-center">
                  <span class="d-inline-flex align-items-center gap-1 toggle-cell" style="cursor: pointer">
                    <span class="value" data-value="0%">0.00%</span>
                  </span>
                </td>
                <td class="text-end">
                  <i data-lucide="eye" style="cursor: pointer;" class="lucide small-icon text-muted eye-toggle"></i>
                </td>
                <td class="text-end">
                  <span class="d-inline-flex align-items-center justify-content-end gap-1 toggle-cell" style="cursor: pointer">
                    <span class="value" style="color: black !important;font-weight:500;" data-value="R$ 0,00">R$ 0,00</span>
                  </span>
                </td>
                <td class="text-end">
                  <i data-lucide="eye" style="cursor: pointer;" class="lucide small-icon text-muted eye-toggle"></i>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

</div>

<script>
 document.addEventListener('DOMContentLoaded', () => {

  document.querySelector('table').addEventListener('click', e => {
    if (e.target.classList.contains('eye-toggle')) {
      const icon = e.target;
      const tdIcon = icon.closest('td');
      if (!tdIcon) return;

      const tdPrev = tdIcon.previousElementSibling;
      const tdNext = tdIcon.nextElementSibling;

      function findValueSpan(td) {
        if (!td) return null;
        return td.querySelector('.value');
      }

      let valueSpan = findValueSpan(tdPrev) || findValueSpan(tdNext);
      if (!valueSpan) return;

      const original = valueSpan.dataset.value.trim();
      const current = valueSpan.textContent.trim();

      if (current !== original) {
        valueSpan.textContent = original;
      } else {
        if (original.startsWith('R$')) {
          valueSpan.textContent = 'R$ ••••';
        } else if (original.endsWith('%')) {
          valueSpan.textContent = '••%';
        } else {
          valueSpan.textContent = '••••';
        }
      }
    }
  });
})
</script>
<!-- Adicione esse script no final do body ou no layout master -->
<script src="https://unpkg.com/lucide@latest"></script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();

    document.querySelectorAll('.card').forEach(card => {
      const btn = card.querySelector('.icon-eye');
      const valor = card.querySelector('.valor-visivel');

      if (!btn || !valor) return;

      btn.dataset.visible = 'true';

      btn.addEventListener('click', () => {
        const isVisible = btn.dataset.visible === 'true';

        if (isVisible) {
          if (valor.textContent.includes('R$')) {
            valor.textContent = 'R$ •••••';
          } else {
            valor.textContent = '•••••';
          }
          btn.setAttribute('data-lucide', 'eye-off');
        } else {
          valor.textContent = valor.dataset.valor;
          btn.setAttribute('data-lucide', 'eye');
        }

        btn.dataset.visible = (!isVisible).toString();

        // Atualiza os ícones do Lucide no DOM, mantendo os listeners
        lucide.replace();
      });
    });
  });
</script>


@endsection