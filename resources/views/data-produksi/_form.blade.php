<div class="mb-3">
    <label class="form-label" for="tanggal">Tanggal</label>
    <input id="tanggal" name="tanggal" type="date" class="form-control @error('tanggal') is-invalid @enderror"
           value="{{ old('tanggal', optional($item ?? null)->tanggal?->format('Y-m-d')) }}">
    @error('tanggal') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="mb-3">
    <label class="form-label" for="area">Area</label>
    <input id="area" name="area" type="text" class="form-control @error('area') is-invalid @enderror"
           value="{{ old('area', ($item ?? null)?->area) }}">
    @error('area') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="mb-3">
    <label class="form-label" for="mesin_peralatan">Mesin / Peralatan</label>
    <input id="mesin_peralatan" name="mesin_peralatan" type="text" class="form-control @error('mesin_peralatan') is-invalid @enderror"
           value="{{ old('mesin_peralatan', ($item ?? null)?->mesin_peralatan) }}">
    @error('mesin_peralatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="mb-3">
    <label class="form-label" for="keterangan">Keterangan</label>
    <textarea id="keterangan" name="keterangan" rows="3" class="form-control @error('keterangan') is-invalid @enderror">{{ old('keterangan', ($item ?? null)?->keterangan) }}</textarea>
    @error('keterangan') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
