<div class="form-group {!! !$errors->has($errorKey) ?: 'has-error' !!}">

    <label for="{{$id}}" class="col-sm-{{$width['label']}} control-label">{{$label}}</label>

    <div class="col-sm-{{$width['field']}}">

        @include('seller.form.error')

        @foreach($options as $option => $label)
            <div class="radio">
                <label>
                    <input type="radio" name="{{$name}}" value="{{$option}}" class="minimal {{$class}}" {{ ($option == old($column, $value))?'checked':'' }} />&nbsp;{{$label}}&nbsp;&nbsp;
                </label>
            </div>
        @endforeach

        @include('seller.form.help-block')

    </div>
</div>
