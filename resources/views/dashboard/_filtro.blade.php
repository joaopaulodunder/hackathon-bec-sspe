<div class="panel panel-hack" id="hidepanel6">

    <div class="panel-heading">
        <h3 class="panel-title ">
            <i class="livicon" data-name="search" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
            Filtro
        </h3>
        <span class="pull-right">
            <i class="glyphicon glyphicon-chevron-up clickable"></i>
        </span>
    </div>

    <div class="panel-body">
        <form action="{{ route('filtro') }}" autocomplete="off" method="POST" role="form">

            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="row">

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Órgãos</label>
                        <select id="orgao" multiple class="form-control" style="height: 200px;">

                            @foreach(\App\HackOrgaos::comboBoxOrgao() as $orgao)

                                <option value="{{$orgao->codigo}}">{{$orgao->codigo}} - {{$orgao->nome}}</option>

                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Unidades Compradora</label>
                        <select id="uge" name="uge[]" multiple class="form-control" style="height: 200px;">

                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Período para Sinergia</label>
                        <div class="radio">
                            <label>
                                <input type="radio" name="periodo" id="periodo1" value="mensal" checked>
                                Mensal
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="periodo" id="periodo2" value="bimestral">
                                Bimestral
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="periodo" id="periodo3" value="trimestral">
                                Trimestral
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="periodo" id="periodo4" value="semestral">
                                Semestral
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="periodo" id="periodo5" value="anual">
                                Anual
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Custo do Processo</label>
                        <div class="col-md-9">
                            <input id="custo" name="custo" type="text" placeholder="" class="form-control">
                        </div>
                    </div>

                </div>

            </div>

            <button type="submit" id="teste" class="btn btn-responsive btn-success">Filtrar</button>
            <button type="reset" class="btn btn-responsive btn-default">Limpar</button>

        </form>
    </div>
</div>