@props(['produto'])

<style>
.pe-section { margin-bottom: 1.75rem; }
.pe-section:last-child { margin-bottom: 0; }
.pe-section-title { font-size: 0.9375rem; font-weight: 600; color: var(--gateway-text-color); margin: 0 0 0.5rem 0; }
.pe-section-desc { font-size: 0.8125rem; color: #64748b; margin: 0 0 0.75rem 0; }
body.dark-mode .pe-section-desc { color: #94a3b8; }
.pe-section-body { border: 1px solid rgba(165,170,177,0.2); border-radius: 10px; padding: 1.25rem; }
body.dark-mode .pe-section-body { border-color: rgba(30,41,59,0.8); }
.pe-field { margin-bottom: 1rem; }
.pe-field:last-child { margin-bottom: 0; }
.pe-field label { font-size: 0.8125rem; font-weight: 500; margin-bottom: 0.375rem; }
.pe-field .form-control { font-size: 0.875rem; border-radius: 8px; }
</style>

<div class="row g-0">
    <div class="col-12">
        <section class="pe-section">
            <h3 class="pe-section-title">Meta ADS</h3>
            <div class="pe-section-body">
                <div class="pe-field">
                    <label for="meta_ads">Pixel ID</label>
                    <input type="text" class="form-control form-control-md" id="meta_ads" name="meta_ads" value="{{ $produto->meta_ads }}" placeholder="Ex: 123456789">
                </div>
            </div>
        </section>
        <section class="pe-section">
            <h3 class="pe-section-title">GTM (Google Tag Manager)</h3>
            <div class="pe-section-body">
                <div class="pe-field">
                    <label for="google_ads">GTM ID</label>
                    <input type="text" class="form-control form-control-md" id="google_ads" name="google_ads" value="{{ $produto->google_ads }}" placeholder="GTM-XXXXXX">
                </div>
            </div>
        </section>
    </div>
</div>
