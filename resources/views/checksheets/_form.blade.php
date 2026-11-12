@php $cs = $checksheet ?? null; @endphp
<div class="mb-3">
    <label class="form-label" for="data_produksi_id">Data produksi</label>
    <select id="data_produksi_id" name="data_produksi_id" class="form-select @error('data_produksi_id') is-invalid @enderror">
        <option value="">— pilih —</option>
        @foreach ($dataProduksi as $dp)
            <option value="{{ $dp->id }}" @selected(old('data_produksi_id', $cs?->data_produksi_id) == $dp->id)>
                {{ $dp->tanggal?->format('d M Y') }} · {{ $dp->area }} · {{ $dp->mesin_peralatan }}
            </option>
        @endforeach
    </select>
    @error('data_produksi_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="mb-3">
    <label class="form-label" for="tanggal_pemeriksaan">Tanggal pemeriksaan</label>
    <input id="tanggal_pemeriksaan" name="tanggal_pemeriksaan" type="date" class="form-control @error('tanggal_pemeriksaan') is-invalid @enderror"
           value="{{ old('tanggal_pemeriksaan', $cs?->tanggal_pemeriksaan?->format('Y-m-d')) }}">
    @error('tanggal_pemeriksaan') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="mb-3">
    <label class="form-label" for="hasil_pemeriksaan">Hasil pemeriksaan</label>
    <textarea id="hasil_pemeriksaan" name="hasil_pemeriksaan" rows="4" class="form-control @error('hasil_pemeriksaan') is-invalid @enderror">{{ old('hasil_pemeriksaan', $cs?->hasil_pemeriksaan) }}</textarea>
    @error('hasil_pemeriksaan') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="mb-3">
    <label class="form-label" for="catatan">Catatan</label>
    <textarea id="catatan" name="catatan" rows="2" class="form-control @error('catatan') is-invalid @enderror">{{ old('catatan', $cs?->catatan) }}</textarea>
    @error('catatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
