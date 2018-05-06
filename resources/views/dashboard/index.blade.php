@extends('template.default')

{{-- Page title --}}
@section('title')
    Dashboard
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')

    <link href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    {{--<link href="{{ asset('assets/vendors/datatables/extensions/Responsive/css/responsive.bootstrap.min.css') }}" rel="stylesheet" type="text/css">--}}
    {{--<link href="{{ asset('assets/css/pages/tables.css') }}" rel="stylesheet" type="text/css" />--}}

@stop

{{-- Page content --}}
@section('content')

    <section class="content">

        <!--START form Filtro-->
        <div class="row">
            <br />
            <div class="col-md-12">
                @include('dashboard._filtro')
            </div>
        </div>
        <!--END form Filtro-->

        <!--START Resultado-->
        @if(isset($sinergia))
            <div class="row">
            <div class="col-md-12">

                <div class="panel panel-hack">

                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="livicon" data-name="barchart" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                            Resultado
                        </h3>
                        <span class="pull-right">
                            <i class="glyphicon glyphicon-chevron-up clickable"></i>
                        </span>
                    </div>

                    <div class="panel-body">

                        @if($estatisticas)


                            <h2 align="center">Global</h2>

                            <div class="row">
                                <?php $nucol = 4; ?>
                                <?php $nome_estatistica = 'Total Gasto Produto (R$)'; ?>
                                <?php $numero_estatistica = number_format($global_estatisticas['total_gasto_produto'], 2, ',', '.'); ?>
                                @include('dashboard._box_estatistica')

                                <?php $nome_estatistica = 'Total Gasto Processo (R$)'; ?>
                                <?php $numero_estatistica = number_format($global_estatisticas['total_gasto_produto'], 2, ',', '.'); ?>
                                @include('dashboard._box_estatistica')

                                <?php $nome_estatistica = 'Total de Gasto (R$)'; ?>
                                <?php $numero_estatistica = number_format($global_estatisticas['total_gasto_produto'] + $global_estatisticas['total_gasto_produto'], 2, ',', '.'); ?>
                                @include('dashboard._box_estatistica')

                            </div>
                            <div class="row">
                                <?php $nome_estatistica = 'Economia Processo (R$)'; ?>
                                <?php $numero_estatistica = number_format($global_estatisticas['economia_processo'], 2, ',', '.'); ?>
                                @include('dashboard._box_estatistica_economia')

                                <?php $nome_estatistica = 'Economia Produto Pelo Menor Preço (R$)'; ?>
                                <?php $numero_estatistica = number_format($global_estatisticas['economia_produto_pelo_menor_preco'], 2, ',', '.'); ?>
                                @include('dashboard._box_estatistica_economia')

                                <?php $nome_estatistica = 'Total Economia (R$)'; ?>
                                <?php $numero_estatistica = number_format($global_estatisticas['economia_processo'] + $global_estatisticas['economia_produto_pelo_menor_preco'], 2, ',', '.'); ?>
                                @include('dashboard._box_estatistica_economia')
                            </div>
                    </div>
                    <br>


                            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

                                @foreach($sinergia as $produto => $periodos)
                                    <div class="panel panel-hack">
                                        <div class="panel-heading" role="tab" id="headingOne">
                                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                                <h4 class="panel-title">{{$produto}}</h4>
                                            </a>
                                        </div>
                                        <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                            <div class="panel-body">

                                                @foreach ($periodos as $periodo => $processos)

                                                    <h2 align="center">Periodo: {{$periodo}} </h2>

                                                    <div class="row">
                                                        {{--<h3>Cenario Atual</h3>--}}
                                                        <?php $nucol = 2; ?>
                                                        <?php $nome_estatistica = 'Processos'; ?>
                                                        <?php $numero_estatistica = $estatisticas[$produto][$periodo]->qtd_processo; ?>
                                                        @include('dashboard._box_estatistica')

                                                        <?php $nome_estatistica = 'Unidades Produto'; ?>
                                                        <?php $numero_estatistica = $estatisticas[$produto][$periodo]->qtd; ?>
                                                        @include('dashboard._box_estatistica')

                                                        <?php $nome_estatistica = 'Menor Preço (R$)'; ?>
                                                        <?php $numero_estatistica = number_format($estatisticas[$produto][$periodo]->menor, 2, ',', '.'); ?>
                                                        @include('dashboard._box_estatistica')

                                                        <?php $nome_estatistica = 'Total Gasto Produto (R$)'; ?>
                                                        <?php $numero_estatistica = number_format($estatisticas[$produto][$periodo]->total_gasto_produto, 2, ',', '.'); ?>
                                                        @include('dashboard._box_estatistica')

                                                        <?php $nome_estatistica = 'Total Gasto Processo (R$)'; ?>
                                                        <?php $numero_estatistica = number_format($estatisticas[$produto][$periodo]->total_gasto_processo, 2, ',', '.'); ?>
                                                        @include('dashboard._box_estatistica')

                                                        <?php $nome_estatistica = 'Total de Gasto (R$)'; ?>
                                                        <?php $numero_estatistica = number_format($estatisticas[$produto][$periodo]->total_gasto_produto + $estatisticas[$produto][$periodo]->total_gasto_processo, 2, ',', '.'); ?>
                                                        @include('dashboard._box_estatistica')

                                                    </div>
                                                    <div class="row">
                                                        <?php $nucol = 4; ?>
                                                        <?php $nome_estatistica = 'Economia Processo (R$)'; ?>
                                                        <?php $numero_estatistica = number_format($estatisticas[$produto][$periodo]->economia_processo, 2 , ',', '.'); ?>
                                                        @include('dashboard._box_estatistica_economia')

                                                        <?php $nome_estatistica = 'Economia Produto Pelo Menor Preço (R$)'; ?>
                                                        <?php $numero_estatistica = number_format($estatisticas[$produto][$periodo]->economia_produto_pelo_menor_preco, 2 , ',', '.'); ?>
                                                        @include('dashboard._box_estatistica_economia')

                                                        <?php $nome_estatistica = 'Total Economia (R$)'; ?>
                                                            <?php $numero_estatistica = number_format($estatisticas[$produto][$periodo]->economia_processo + $estatisticas[$produto][$periodo]->economia_produto_pelo_menor_preco, 2, ',', '.'); ?>
                                                        @include('dashboard._box_estatistica_economia')
                                                    </div>

                                                    <div class="row">
                                                        <br>
                                                        <?php $processos = $processos; ?>
                                                        @include('dashboard._grid')
                                                    </div>

                                                @endforeach

                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p>Nenhum Resultado foi encontrado.</p>
                            @endif
                        </div>

                    </div>
                </div>

            </div>
        </div>
        @endif
        <!--END Resultado-->


    </section>

@stop

{{-- page level scripts --}}
@section('footer_scripts')

    <script src="{{ asset('assets/js/josh.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/metisMenu.js') }}" type="text/javascript"> </script>
{{--    <script src="{{ asset('assets/vendors/holder/holder.js') }}" type="text/javascript"></script>--}}

    <script src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.min.js') }}"></script>
{{--    <script src="{{ asset('assets/vendors/datatables/extensions/Responsive/js/dataTables.responsive.min.js') }}"></script>--}}
    <script src="{{ asset('assets/js/pages/table-responsive.js') }}"></script>

    <script type="text/javascript">

        $(document).ready(function(){

            $( "#orgao" ).change(function() {

                var orgao = $(this).val();

                $("#uge").empty();

                $.ajax({
                    url:'{{Route('data-uge')}}/' + orgao,
                    type:'GET',
                    dataType: 'json',
                    success: function( json ) {
                        $.each(json, function(id, value) {
                            $("#uge").append("<option value='"+value['id']+"'>"+value['nome']+"</option>");
                        });
                    }
                });

            });
        });

    </script>

@stop
