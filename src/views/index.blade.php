<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/jquery@3.3.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue@2.5.17/dist/vue.js"></script>

<form id="app" class="container" action="{{ url('/el-mag/translate-it/generate') }}" method="post">
    <h1 class="my-4">Create Translated Model & Migration</h1>
    <hr/>

    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="form-group col-4">
                    <label>Model Name</label>
                    <input type="text" name="model_name" class="form-control"/>
                </div>
            </div>
            <div class="row">
                @foreach($flags as $flag)
                    <label class="col-2">
                        <input type="checkbox" name="flag[{{ $flag }}]" {{ $flag == 'timestamps' ? 'checked' : '' }}/>
                        <span>{{ $flag }}</span>
                    </label>
                @endforeach
            </div>
        </div>
    </div>

    <div v-for="(col, i) in columns" :key="col">
        <div class="row">
            <div class="col-2 form-group">
                <label>Type Name</label>
                <select :name="'columns[' + col + '][typename]'" class="form-control">
                    <option value=""></option>
                    @foreach($types as $type)
                        <option value="{{ $type }}">{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-2 form-group">
                <label>Column Name</label>
                <input :name="'columns[' + col + '][column_name]'" class="form-control"/>
            </div>
            <div class="col-2 form-group">
                <label>Argument #0</label>
                <input :name="'columns[' + col + '][arg0]'" class="form-control"/>
            </div>
            <div class="col-2 form-group">
                <label>Argument #1</label>
                <input :name="'columns[' + col + '][arg1]'" class="form-control"/>
            </div>
            <div class="col-2 form-group">
                <label>Default Value</label>
                <input :name="'columns[' + col + '][default]'" class="form-control"/>
            </div>
            <div class="col-2 text-right">
                <label class="d-block">&nbsp;</label>
                <button type="button" class="btn btn-danger" @click="removeFrom(columns, i)">Remove Column</button>
            </div>
        </div>

        <div class="row">
            <label class="col-2">
                <input type="checkbox" :name="'columns[' + col + '][flag][autoIncrement]'"/>
                <span>Auto Increment</span>
            </label>
            <label class="col-2">
                <input type="checkbox" :name="'columns[' + col + '][flag][useCurrent]'"/>
                <span>Use Current</span>
            </label>
            <label class="col-2">
                <input type="checkbox" :name="'columns[' + col + '][flag][nullable]'"/>
                <span>Nullable</span>
            </label>
            <label class="col-2">
                <input type="checkbox" :name="'columns[' + col + '][flag][unsigned]'"/>
                <span>Unsigned</span>
            </label>
            <label class="col-2">
                <input type="checkbox" :name="'columns[' + col + '][flag][index]'"/>
                <span>Index</span>
            </label>
            <label class="col-2">
                <input type="checkbox" :name="'columns[' + col + '][flag][unique]'"/>
                <span>Unique</span>
            </label>
        </div>

        <hr/>
    </div>

    <div class="text-right">
        <button type="button" class="btn btn-warning" @click="addTo(columns)">Add Column</button>
    </div>

    <h3>Translated Attributes</h3>
    <hr/>

    <div v-for="(col, i) in translated" :key="col">
        <div class="row">
            <div class="col-2 form-group">
                <label>Type Name</label>
                <select :name="'translated[' + col + '][typename]'" class="form-control">
                    <option value=""></option>
                    @foreach($types as $type)
                        <option value="{{ $type }}">{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-2 form-group">
                <label>Column Name</label>
                <input :name="'translated[' + col + '][column_name]'" class="form-control"/>
            </div>
            <div class="col-2 form-group">
                <label>Argument #0</label>
                <input :name="'translated[' + col + '][arg0]'" class="form-control"/>
            </div>
            <div class="col-2 form-group">
                <label>Argument #1</label>
                <input :name="'translated[' + col + '][arg1]'" class="form-control"/>
            </div>
            <div class="col-2 form-group">
                <label>Default Value</label>
                <input :name="'translated[' + col + '][default]'" class="form-control"/>
            </div>
            <div class="col-2 text-right">
                <label class="d-block">&nbsp;</label>
                <button type="button" class="btn btn-danger" @click="removeFrom(translated, i)">Remove Column</button>
            </div>
        </div>

        <div class="row">
            <label class="col-2">
                <input type="checkbox" :name="'translated[' + col + '][flag][autoIncrement]'"/>
                <span>Auto Increment</span>
            </label>
            <label class="col-2">
                <input type="checkbox" :name="'translated[' + col + '][flag][useCurrent]'"/>
                <span>Use Current</span>
            </label>
            <label class="col-2">
                <input type="checkbox" :name="'translated[' + col + '][flag][nullable]'"/>
                <span>Nullable</span>
            </label>
            <label class="col-2">
                <input type="checkbox" :name="'translated[' + col + '][flag][unsigned]'"/>
                <span>Unsigned</span>
            </label>
            <label class="col-2">
                <input type="checkbox" :name="'translated[' + col + '][flag][index]'"/>
                <span>Index</span>
            </label>
            <label class="col-2">
                <input type="checkbox" :name="'translated[' + col + '][flag][unique]'"/>
                <span>Unique</span>
            </label>
        </div>

        <hr/>
    </div>

    <div class="text-right">
        <button type="button" class="btn btn-warning" @click="addTo(translated)">Add Column</button>
    </div>


    <div class="text-right">
        <button class="btn btn-primary">Save!</button>
    </div>
</form>

<script>
    let app = new Vue({
        el: '#app',
        delimiters: ['{@', '@}'],
        data: {
            columns: [],
            translated: [],
        },
        methods: {
            addTo(list) {
                list.push(Math.random());
            },
            removeFrom(list, index) {
                list.splice(index, 1);
            },
        },
    });
</script>