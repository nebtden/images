<div class="form-group {!! !$errors->has($errorKey) ?: 'has-error' !!}">

    <label for="{{$id}}" class="col-sm-{{$width['label']}} control-label">{{$label}}</label>

    <div class="col-sm-{{$width['field']}}">

        @include('seller.form.error')

        <select class="form-control {{$class}}" style="width: 100%;" name="{{$name}}[]" multiple="multiple" data-placeholder="{{ $placeholder }}" {!! $attributes !!} >
            @foreach($value as $select)
                <option value="{{$select}}" selected>{{$select}}</option>
            @endforeach
        </select>
        <input type="hidden" name="{{$name}}[]" />

        @include('seller.form.help-block')

    </div>
</div>
