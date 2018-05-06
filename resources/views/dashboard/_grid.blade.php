<div class="portlet box primary">
    <div class="portlet-title">
        <div class="caption">
            <i class="livicon" data-name="responsive" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
            Listagem com Sinergia
        </div>
    </div>
    <div class="portlet-body flip-scroll">
        <table class="table table-bordered table-striped table-condensed flip-content">
            <thead class="flip-content">
            <tr>
                <th>OC</th>
                <th class="numeric">Número</th>
                <th>Situação</th>
                <th>Data Abertura</th>
                <th>Órgão</th>
                <th class="numeric">Quantidade</th>
                <th class="numeric">Preço</th>
                <th>Vencedor</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($processos as $processo)
                <tr>
                    <td>{{$processo->nu_licitacao}}</td>
                    <td class="numeric">{{$processo->nu_item}}</td>
                    <td>{{$processo->status_item}}</td>
                    <td>
                        {{\App\Util::formatData($processo->data)}}
                    </td>
                    <td>{{$processo->uge}}</td>
                    <td class="numeric">{{$processo->nu_quant}}</td>
                    <td class="numeric">
                        @if($processo->valor_vencedor)
                            {{$processo->valor_vencedor}}
                        @endif
                    </td>
                    <td>
                        @if($processo->vencedor)
                            {{$processo->vencedor}}
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>