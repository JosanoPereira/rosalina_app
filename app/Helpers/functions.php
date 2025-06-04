<?php

use App\Models\Adiantamento;
use App\Models\BancosEmployee;
use App\Models\Contacto;
use App\Models\Contracto;
use App\Models\Dependente;
use App\Models\Efectividade;
use App\Models\Employee;
use App\Models\HistoricoPagamento;
use App\Models\Iten;
use App\Models\SaidaStock;
use App\Models\Salario;
use App\Models\Stock;
use App\Models\SubsidiosEmployee;
use App\Models\TipoDocumento;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use phputil\extenso\Extenso;


function data_formatada($data, $formato = 'd-m-Y')
{
    if (is_null($data))
        return null;
    return date($formato, strtotime($data));
}

function gerar_codigo($length = 20)
{
    #$characters = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz,._+*%$!&#";
    $characters = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function generateQRCodeText($text, $size = 50)
{

    return base64_encode(QrCode::format('svg')
        ->size($size)
        // ->style('dot')
//        ->color(30,30,10)
//        ->eye('circle')
        ->generate(
            $text,
        ));
}


function checkIfPrimeirosOitoSaoNumeros($string)
{
    // Extraia os primeiros 8 caracteres da string
    $primeirosOito = substr($string, 0, 8);

    // Verifique se os primeiros 8 caracteres são todos dígitos
    return ctype_digit($primeirosOito);
}

function employees_by_id($id)
{

    $employee = DB::connection('mysql_gabinata')
        ->table('funcionarios_clientes')
        ->join('clientes', 'clientes.id', 'funcionarios_clientes.clientes_id')
        ->where('funcionarios_clientes.id', $id)
        ->get([
            '*',
            'funcionarios_clientes.id as id',
            'funcionarios_clientes.nome as nome',
            'clientes.nome as empresa',
        ])->first();
    return $employee;
}


function visitantes_by_id($id)
{
    return \App\Models\Visitante::all()->find($id);
}

function alunos_by_id($id)
{
    return DB::connection('mysql_booking')
        ->table('alunos')
        ->join('pessoas', 'pessoas.id', 'alunos.pessoas_id')
        ->where('alunos.id', $id)->get()->last();

}

function numero_documento($entidade)
{

    if ($entidade) {
        if (!$entidade->sigla)
            $entidade->sigla = tipo_documento($entidade->tipos_id)->sigla;

        return $entidade->sigla . ' ' . $entidade->ano . '/' . $entidade->numero;
    }
    return null;

}


function tipo_documento($id)
{
    return DB::connection('mysql_booking')->table('tipos')->find($id);
}

function getUserByIDBooking($id)
{
    return DB::connection('mysql_booking')->table('users')->find($id);
}

function facturaNC2($nc_id)
{
    $res = DB::connection('mysql_booking')
        ->table('nota_creditos')
        ->where('nota_creditos_id', $nc_id)->get()->last();

    if ($res)
        return $res->ref_factura;
    return null;

}

function cambio()
{
    return DB::connection('mysql_booking')->table('taxa_cambio')->get()->first();
}

function cambio_bna()
{
    return DB::connection('mysql_booking')->table('taxa_cambio')->get()->last();
}

function semana_do_ano()
{

    $dia = data_formatada(now(), 'd');
    $mes = data_formatada(now(), 'm');
    $ano = data_formatada(now(), 'Y');

    $var = intval(date('z', mktime(0, 0, 0, $mes, $dia, $ano)) / 7) + 1;

    return $var;
}

function formador($turmas_id)
{

    $inscricao = DB::connection('mysql_booking')
        ->table('turmas')
        ->join('formadores', 'formadores.id', 'turmas.formadores_id')
        ->join('funcionarios', 'funcionarios.id', 'formadores.funcionarios_id')
        ->join('pessoas', 'pessoas.id', 'funcionarios.pessoas_id')
        ->where('turmas.id', $turmas_id)
        ->get();

    if ($inscricao->isEmpty())
        return null;
    return $inscricao->last()->nome;
}

function coordenadas_bancaria_funcionario($employee)
{
    if ($employee) {
        $bancos = BancosEmployee::all()->where('employees_id', $employee->id)->last();
        return $bancos;
    }
    return null;
}


function extenso($valor, $moeda = 1)
{
    $e = new Extenso();

    $res = str_replace('real', 'kwanza', str_replace('reais', 'kwanzas', $e->extenso($valor)));
    if ($moeda == 2) {
        $res = str_replace('real', 'dollar', str_replace('reais', 'dollares', $e->extenso($valor)));
    }

    //$res = str_replace('real', '', str_replace('reais', '', $e->extenso( $valor )));

    return capitalizeNomeProprio(str_replace('centavos', 'cêntimos', $res));
}

function capitalizeNomeProprio($nome)
{
    $partesNome = explode(" ", $nome);
    $exclude = array('da', 'das', 'de', 'do', 'dos', 'e');
    $nomeCapitalizado = '';
    foreach ($partesNome as $parte) {
        if (in_array(strtolower($parte), $exclude)) {
            $parte = strtolower($parte);
        } else {
            $parte = ucfirst($parte);
        }
        $nomeCapitalizado .= $parte . " ";
    }
    return trim($nomeCapitalizado);
}


function numeros_com_algarismo($numero, $algarismo = 3)
{
    return str_pad($numero, $algarismo, '0', STR_PAD_LEFT);
}

function sendSms($telemovel, $msg)
{
    file_get_contents("http://netsms.co.ao/app/appi/?accao=enviar_sms&chave_entidade=" . env('ENTIDADE_SMS') . "&destinatario=" . $telemovel . "&descricao_sms=" . urlencode($msg));
}

function send_sms($telemovel, $mensagem)
{
    $tel = str_replace('+244', '', str_replace(' ', '', $telemovel));

    //$entidade = 'YS565H6egfcJed652sd6E52TFsK';//MAKETEC
    //$entidade = 'Y5cEHT66s256FgsfS2d5e56JeKd';//SERCONOIL
    $entidade = env('ENTIDADE_SMS'); //OPERATEC

    file_get_contents("http://netsms.co.ao/app/appi/?accao=enviar_sms&chave_entidade=" . $entidade . "&destinatario=" . $tel . "&descricao_sms=" . urlencode($mensagem));

    echo "SMS enviado" . "<br>";
}

function gerarMensagemAniversarioAleatoria()
{
    $mensagensAniversario = [
        "A Família Operatec deseja um aniversário repleto de alegrias e realizações para você!",
        "Parabéns! A Família Operatec está feliz em celebrar este dia especial com você.",
        "Que este novo ano de vida seja iluminado! A Família Operatec deseja um feliz aniversário.",
        "A Família Operatec se une para desejar um dia incrível e um ano cheio de conquistas para você.",
        "Parabéns pelo seu aniversário! A Família Operatec está torcendo por seu sucesso contínuo.",
        "Que a alegria deste dia se estenda por todo o ano! A Família Operatec deseja felicidades sem fim.",
        "A Família Operatec celebra este dia especial ao seu lado. Parabéns e muitas realizações!",
        "Feliz aniversário! Que a Família Operatec seja sempre um suporte em sua jornada.",
        "A Família Operatec se alegra em comemorar mais um ano de sua vida. Parabéns pelo seu dia!",
        "Que a Família Operatec esteja sempre presente para celebrar seus sucessos. Feliz aniversário!",
        "Parabéns! A Família Operatec deseja um dia cheio de sorrisos e momentos inesquecíveis.",
        "A Família Operatec se une para desejar um aniversário repleto de felicidade e prosperidade.",
        "Que este novo ciclo que se inicia seja repleto de realizações. A Família Operatec está com você!",
        "A Família Operatec deseja um aniversário cheio de conquistas e momentos especiais para você.",
        "Parabéns pelo seu aniversário! Que a Família Operatec continue sendo parte de seus melhores momentos.",
        "A Família Operatec celebra a pessoa incrível que você é. Feliz aniversário e muitas felicidades!",
        "Que a Família Operatec seja sempre o alicerce para seus sonhos. Parabéns pelo seu dia!",
        "A Família Operatec deseja um aniversário cheio de alegrias e realizações para um colaborador tão especial como você.",
        "Parabéns! Que a Família Operatec seja sempre fonte de apoio e inspiração em sua jornada.",
        "A Família Operatec celebra mais um ano da sua vida. Que seja um ano de grandes conquistas e felicidades!",
        "Que a Família Operatec seja a fonte de inspiração para suas conquistas. Parabéns pelo seu aniversário!",
        "A Família Operatec deseja um dia cheio de amor e momentos especiais. Feliz aniversário!",
        "Parabéns! Que cada passo que você der na Operatec seja de sucesso e realização.",
        "A Família Operatec celebra a dádiva que é ter você como parte dela. Feliz aniversário!",
        "Que a Operatec continue sendo o palco de suas maiores vitórias. Parabéns pelo seu dia!",
        "A Família Operatec se une para desejar um aniversário repleto de sorrisos e boas surpresas.",
        "Parabéns pelo seu aniversário! Que a Operatec seja o cenário de muitas felicidades em sua vida.",
        "A Família Operatec deseja um aniversário cheio de prosperidade e realizações pessoais e profissionais.",
        "Que a Operatec seja sempre um lugar onde seus sonhos se tornam realidade. Feliz aniversário!",
        "Parabéns! Que cada dia na Operatec seja um capítulo emocionante em sua história de sucesso.",
        "A Família Operatec celebra a singularidade que você traz para a equipe. Feliz aniversário!",
        "Que a Operatec seja sempre um espaço de crescimento e aprendizado. Parabéns pelo seu dia!",
        "A Família Operatec deseja um aniversário cheio de realizações e momentos memoráveis.",
        "Parabéns pelo seu aniversário! Que a Operatec seja abençoada por ter você como parte dela.",
        "Que a Operatec continue sendo um lugar onde seus talentos se destacam. Feliz aniversário!",
        "A Família Operatec se alegra em compartilhar este dia especial com você. Parabéns!",
        "Parabéns! Que a Operatec seja sempre um ambiente de trabalho onde você se sinta valorizado e feliz.",
        "A Família Operatec deseja um aniversário cheio de paz, saúde e sucesso em todos os aspectos da vida.",
        "Que a Operatec seja o palco de muitas celebrações ao longo do ano. Feliz aniversário!",
        "Parabéns pelo seu aniversário! Que a Operatec seja um lugar de realizações extraordinárias.",
        "A Família Operatec celebra a jornada única que é a sua vida. Feliz aniversário!",
        "Que a Operatec continue sendo um espaço de oportunidades e crescimento constante. Parabéns!",
        "A Família Operatec deseja um aniversário cheio de momentos felizes e conquistas significativas.",
        "Parabéns! Que a Operatec seja sempre um lugar onde seus esforços são reconhecidos e valorizados.",
        "Que a Operatec seja abençoada com mais um ano da sua incrível contribuição. Feliz aniversário!",
        "A Família Operatec se une para desejar um dia cheio de alegria e um futuro brilhante. Parabéns!",
        "Parabéns pelo seu aniversário! Que a Operatec seja o palco de muitos êxitos em sua carreira.",
        "A Família Operatec deseja um aniversário repleto de realizações e momentos inesquecíveis.",
        "Que a Operatec seja sempre um lugar onde suas ideias prosperam. Feliz aniversário!",
        "A Família Operatec celebra a data especial que marca o início de mais um ano incrível em sua vida. Parabéns!",
        "Parabéns! Que a Operatec continue sendo um ambiente de trabalho onde você se sente realizado e motivado.",
        "A Família Operatec deseja um aniversário cheio de novas oportunidades e experiências enriquecedoras.",
        "Que a Operatec seja sempre um lugar de camaradagem e crescimento mútuo. Feliz aniversário!",
        "Parabéns pelo seu aniversário! Que a Operatec seja o cenário de muitos momentos de felicidade.",
        "A Família Operatec se alegra em celebrar mais um ano da sua vida. Parabéns!",
        "Que a Operatec continue sendo um espaço de inovação e realização pessoal. Feliz aniversário!",
        "Parabéns! Que cada dia na Operatec seja uma oportunidade para alcançar novas metas e conquistas.",
        "A Família Operatec deseja um aniversário cheio de surpresas boas e momentos gratificantes.",
        "Que a Operatec seja sempre um lugar onde você se sente valorizado e inspirado. Feliz aniversário!",
        "A Família Operatec celebra a pessoa incrível que você é. Parabéns pelo seu dia!",
        "Parabéns pelo seu aniversário! Que a Operatec seja o palco de muitas vitórias e realizações.",
        "A Família Operatec deseja um ano cheio de crescimento e sucesso em todas as áreas da sua vida. Feliz aniversário!",
        "Que a Operatec seja sempre um ambiente de trabalho onde seus objetivos se tornam realidade. Parabéns!",
        "Parabéns! Que cada momento na Operatec seja uma oportunidade para alcançar novos patamares de excelência.",
        "A Família Operatec deseja um aniversário cheio de boas energias e realizações extraordinárias.",
        "Que a Operatec seja abençoada com mais um ano da sua contribuição excepcional. Feliz aniversário!",
        // ... Mensagens anteriores

        "Que a Operatec continue sendo um espaço onde seus talentos se desenvolvem. Feliz aniversário!",
        "Parabéns! Que cada aniversário seja um novo capítulo de sucessos na sua história na Operatec.",
        "A Família Operatec deseja um dia cheio de alegrias e um futuro brilhante para você. Feliz aniversário!",
        "Que a Operatec seja sempre um ambiente de trabalho que incentiva a inovação. Parabéns pelo seu dia!",
        "A Família Operatec celebra a pessoa incrível que você é. Feliz aniversário e muitas conquistas!",
        "Parabéns pelo seu aniversário! Que a Operatec seja abençoada por ter você como parte essencial dela.",
        "Que cada dia na Operatec seja uma oportunidade para aprender e crescer. Feliz aniversário!",
        "A Família Operatec deseja um aniversário cheio de realizações e momentos inesquecíveis para você.",
        "Parabéns! Que a Operatec continue sendo o lugar onde seus sonhos se tornam realidade.",
        "A Família Operatec celebra a data especial que é o seu aniversário. Que seja um dia incrível!",
        "Que a Operatec seja sempre um espaço de colaboração e realizações mútuas. Feliz aniversário!",
        "Parabéns pelo seu aniversário! Que cada ano na Operatec seja mais extraordinário que o anterior.",
        "A Família Operatec deseja um aniversário cheio de alegrias, saúde e sucesso em todos os aspectos.",
        "Que a Operatec seja abençoada com mais um ano da sua valiosa contribuição. Parabéns!",
        "Parabéns! Que a Operatec seja sempre um ambiente onde você se sente inspirado e motivado.",
        "A Família Operatec se alegra em compartilhar mais um aniversário ao seu lado. Feliz aniversário!",
        "Que a Operatec seja o palco de muitas celebrações e realizações ao longo da sua carreira. Parabéns!",
        "Parabéns pelo seu aniversário! Que a Operatec seja um lugar onde suas ideias ganham vida.",
        "A Família Operatec deseja um aniversário cheio de boas surpresas e momentos gratificantes.",
        "Que a Operatec seja sempre um espaço de apoio e camaradagem. Feliz aniversário!",
        "Parabéns! Que cada dia na Operatec seja um passo em direção a um futuro repleto de conquistas.",
        "A Família Operatec celebra mais um ano da sua vida. Que seja um ano cheio de realizações!",
        "Que a Operatec continue sendo um ambiente de trabalho onde sua paixão pelo que faz se destaca. Parabéns!",
        "Parabéns pelo seu aniversário! Que a Operatec seja abençoada com muitos momentos de sucesso.",
        "A Família Operatec deseja um aniversário repleto de felicidades e novas oportunidades para você.",
        "Que a Operatec seja sempre um lugar de aprendizado contínuo e crescimento profissional. Feliz aniversário!",
        "Parabéns! Que a Operatec seja o cenário de muitos sorrisos e realizações ao longo do ano.",
        "A Família Operatec se une para desejar um aniversário cheio de saúde, amor e prosperidade.",
        "Que a Operatec seja abençoada com mais um ano da sua dedicação exemplar. Parabéns!",
        "Parabéns pelo seu aniversário! Que a Operatec seja sempre um ambiente onde seus objetivos se concretizam.",
        "A Família Operatec deseja um dia incrível e um futuro brilhante para um colaborador tão especial. Feliz aniversário!",
        "Que a Operatec continue sendo um lugar onde suas habilidades são reconhecidas e valorizadas. Parabéns!",
        "Parabéns! Que cada ano na Operatec seja uma jornada de descobertas e realizações extraordinárias.",
        "A Família Operatec celebra mais um ano da sua presença inspiradora. Que seja um ano incrível!",
        "Que a Operatec seja sempre um ambiente onde suas metas profissionais e pessoais se realizam. Feliz aniversário!",
        "Parabéns pelo seu aniversário! Que a Operatec seja abençoada com muitos momentos de sucesso e felicidade.",
        "A Família Operatec deseja um aniversário cheio de sorrisos, conquistas e momentos especiais.",
        "Que a Operatec seja um espaço onde sua criatividade floresce. Parabéns pelo seu dia!",
        "Parabéns! Que a Operatec seja sempre um lugar onde você se sente apoiado e valorizado.",
        "A Família Operatec celebra a singularidade que você traz para a equipe. Feliz aniversário!",
        "Que a Operatec continue sendo um ambiente de trabalho onde seus esforços são reconhecidos. Parabéns!",
        "Parabéns pelo seu aniversário! Que a Operatec seja abençoada por ter um colaborador tão dedicado.",
        "A Família Operatec deseja um aniversário repleto de realizações e momentos memoráveis para você.",
        "Que a Operatec seja sempre um lugar onde seus desafios se transformam em oportunidades. Feliz aniversário!",
        "Parabéns! Que cada ano na Operatec seja um novo capítulo de sucesso e crescimento.",
        "A Família Operatec celebra a jornada extraordinária que é a sua vida. Feliz aniversário!",
        "Que a Operatec continue sendo um espaço onde sua paixão pelo trabalho brilha. Parabéns pelo seu dia!",
        "Parabéns pelo seu aniversário! Que a Operatec seja um lugar de aprendizado contínuo e conquistas.",
        "A Família Operatec deseja um aniversário cheio de boas energias e realizações extraordinárias.",
        "Que a Operatec seja abençoada com mais um ano da sua valiosa contribuição. Parabéns!",
        "Parabéns! Que a Operatec seja sempre um ambiente onde seus talentos são cultivados e reconhecidos.",
        "A Família Operatec se alegra em compartilhar mais um aniversário ao seu lado. Feliz aniversário!",
        "Que a Operatec seja o palco de muitos momentos de orgulho e sucesso. Parabéns pelo seu dia!",
        "Parabéns pelo seu aniversário! Que a Operatec seja abençoada por ter você como parte essencial dela.",
        "A Família Operatec deseja um aniversário cheio de alegrias, saúde e sucesso em todos os aspectos.",
        "Que cada dia na Operatec seja uma oportunidade para aprender e crescer. Feliz aniversário!",
        "A Família Operatec celebra a pessoa incrível que você é. Feliz aniversário e muitas conquistas!",
        "Parabéns pelo seu aniversário! Que a Operatec seja abençoada por ter você como parte dela.",
        "Que cada dia na Operatec seja uma jornada de sucesso e satisfação. Parabéns!",
        "A Família Operatec deseja um aniversário repleto de realizações e momentos inesquecíveis para você.",
        "Parabéns! Que a Operatec continue sendo o lugar onde seus sonhos se tornam realidade.",
        "A Família Operatec celebra a data especial que é o seu aniversário. Que seja um dia incrível!",
        "Que a Operatec seja sempre um espaço de colaboração e realizações mútuas. Feliz aniversário!",
        "Parabéns pelo seu aniversário! Que cada ano na Operatec seja mais extraordinário que o anterior.",
        "A Família Operatec deseja um aniversário cheio de alegrias, saúde e sucesso em todos os aspectos.",
        "Que a Operatec seja abençoada com mais um ano da sua valiosa contribuição. Parabéns!",
        "Parabéns! Que a Operatec seja sempre um ambiente onde você se sente inspirado e motivado.",
        "A Família Operatec se alegra em compartilhar mais um aniversário ao seu lado. Feliz aniversário!",
        "Que a Operatec seja o palco de muitas celebrações e realizações ao longo da sua carreira. Parabéns!",
        "Parabéns pelo seu aniversário! Que a Operatec seja um lugar onde suas ideias ganham vida.",
        "A Família Operatec deseja um aniversário cheio de boas surpresas e momentos gratificantes.",
        "Que a Operatec seja sempre um espaço de apoio e camaradagem. Feliz aniversário!",
        "Parabéns! Que cada dia na Operatec seja um passo em direção a um futuro repleto de conquistas.",
        "A Família Operatec celebra mais um ano da sua presença inspiradora. Que seja um ano incrível!",
        "Que a Operatec seja sempre um ambiente onde suas metas profissionais e pessoais se realizam. Feliz aniversário!",
        "Parabéns pelo seu aniversário! Que a Operatec seja abençoada com muitos momentos de sucesso e felicidade.",
        "A Família Operatec deseja um aniversário cheio de sorrisos, conquistas e momentos especiais.",
        "Que a Operatec seja um espaço onde sua criatividade floresce. Parabéns pelo seu dia!",
        "Parabéns! Que a Operatec seja sempre um lugar onde você se sente apoiado e valorizado.",
        "A Família Operatec celebra a singularidade que você traz para a equipe. Feliz aniversário!",
        "Que a Operatec continue sendo um ambiente de trabalho onde seus esforços são reconhecidos. Parabéns!",
        "Parabéns pelo seu aniversário! Que a Operatec seja abençoada por ter um colaborador tão dedicado.",
        "A Família Operatec deseja um aniversário repleto de realizações e momentos memoráveis para você.",
        "Que a Operatec seja sempre um lugar onde seus desafios se transformam em oportunidades. Feliz aniversário!",
        "Parabéns! Que cada ano na Operatec seja um novo capítulo de sucesso e crescimento.",
        "A Família Operatec celebra a jornada extraordinária que é a sua vida. Feliz aniversário!",
        "Que a Operatec continue sendo um espaço onde sua paixão pelo trabalho brilha. Parabéns pelo seu dia!",
        "Parabéns pelo seu aniversário! Que a Operatec seja um lugar de aprendizado contínuo e conquistas.",
        "A Família Operatec deseja um aniversário cheio de boas energias e realizações extraordinárias.",
        "Que a Operatec seja abençoada com mais um ano da sua valiosa contribuição. Parabéns!",
        "Parabéns! Que a Operatec seja sempre um ambiente onde seus talentos são cultivados e reconhecidos.",
        "A Família Operatec se alegra em compartilhar mais um aniversário ao seu lado. Feliz aniversário!",
        "Que a Operatec seja o palco de muitos momentos de orgulho e sucesso. Parabéns pelo seu dia!",
        "Parabéns pelo seu aniversário! Que a Operatec seja abençoada por ter você como parte essencial dela.",
        "A Família Operatec deseja um aniversário cheio de alegrias, saúde e sucesso em todos os aspectos.",
        "Que cada dia na Operatec seja uma oportunidade para aprender e crescer. Feliz aniversário!",
        "A Família Operatec celebra a pessoa incrível que você é. Feliz aniversário e muitas conquistas!",
        "Parabéns pelo seu aniversário! Que a Operatec seja abençoada por ter você como parte dela.",
        "Que cada dia na Operatec seja uma jornada de sucesso e satisfação. Parabéns!",
        "A Família Operatec deseja um aniversário repleto de realizações e momentos inesquecíveis para você.",
        "Parabéns! Que a Operatec continue sendo o lugar onde seus sonhos se tornam realidade.",
        "A Família Operatec celebra a data especial que é o seu aniversário. Que seja um dia incrível!",
        "Que a Operatec seja sempre um espaço de colaboração e realizações mútuas. Feliz aniversário!",
        "Parabéns pelo seu aniversário! Que cada ano na Operatec seja mais extraordinário que o anterior.",
        "A Família Operatec deseja um aniversário cheio de alegrias, saúde e sucesso em todos os aspectos.",
        "Que a Operatec seja abençoada com mais um ano da sua valiosa contribuição. Parabéns!",

    ];


    //global $mensagensAniversario; // Tornando a matriz acessível dentro da função

    $indiceAleatorio = array_rand($mensagensAniversario);
    return $mensagensAniversario[$indiceAleatorio];
}

function users_profile()
{
    $emp = DB::table('users')
        ->leftJoin('employees', 'employees.id', 'users.employees_id')
        ->leftJoin('pessoas', 'pessoas.id', 'employees.pessoas_id')
        ->where('users.id', Auth::id())
        ->get();

    $img_profile = is_null($emp->first()->foto) ? asset('img/loggin.png') : url('storage/' . $emp->first()->foto);
    return [
        'short_name' => primeiro_ultimo($emp->first()->nome),
        'img_profile' => $img_profile,
    ];
}

function dias_restantes($data)
{
    if (is_null($data))
        return null;
    $data_actual = Carbon::now();
    $data_validade = Carbon::parse($data);
    $dias_restantes = $data_actual->diffInDays($data_validade, false);
    return $dias_restantes;
}

function isUpdatedEmployee($employees_id)
{
    $employee = Employee::all()->find($employees_id);

    if ($employee) {
        if ($employee->data_admissao == null)
            return false;
        elseif ($employee->nif == null)
            return false;
        elseif ($employee->inss == null)
            return false;
        elseif ($employee->pessoas->data_nascimento == null)
            return false;
        elseif ($employee->pessoas->nacionalidades_id == null)
            return false;
        elseif ($employee->pessoas->enderecos_id == null)
            return false;
        elseif ($employee->pessoas->generos_id == null)
            return false;
        elseif ($employee->pessoas->civil_estados_id == null)
            return false;
        else {
            return true;
        }
    } else {
        return false;
    }
}

function aniversariantes()
{
    $data_actual = Carbon::now();
    $mes_actual = $data_actual->month;
    $dia_actual = $data_actual->day;
    $aniversariantes = DB::table('pessoas')
        ->join('employees', 'employees.pessoas_id', 'pessoas.id')
        ->where('employees.status', 1)
        ->whereMonth('data_nascimento', $mes_actual)
        ->whereDay('data_nascimento', '>=', $dia_actual)
        //->limit(4)
        ->get()
        ->sortBy(function ($aniversariantes) {
            return Carbon::parse($aniversariantes->data_nascimento)->day;
        });
    return $aniversariantes;
}

function getContactos($pessoas_id)
{
    return Contacto::all()->where('pessoas_id', $pessoas_id)->first();
}

function gerador_password($tam = 8)
{
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < $tam; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}


function formatar_moeda($valor)
{
    return number_format($valor, '2', ',', '.');
}

function contacto($pessoas_id)
{
    return Contacto::all()->where('pessoas_id', $pessoas_id);
}

function check_file_exist($path)
{
    if (is_null($path))
        return false;
    return Storage::disk('public')->exists($path);
}

function deleteDir($dir)
{
    if (Storage::exists($dir)) {
        Storage::deleteDirectory($dir);
    }
}


function diferenca_datas($data_inicial, $data_final)
{
    // Calcula a diferença em segundos entre as datas
    $diferenca = strtotime($data_final) - strtotime($data_inicial);

    //Calcula a diferença em dias
    $dias = floor($diferenca / (60 * 60 * 24));
    return $dias;
}

function datadiffActual($data2, $data1 = 'now')
{
    $d1 = new DateTime($data1);
    $d2 = new DateTime($data2);
    $intervalo = $d1->diff($d2);

    return diferenca_datas($data1, $data2);
}


function primeiro_ultimo($fullname)
{
    $res = explode(' ', $fullname);
    return $res[0] . ' ' . $res[count($res) - 1];
}

function username_create($fullname)
{
    $res = explode(' ', $fullname);
    return strtolower(remover_acentos($res[0] . '.' . $res[count($res) - 1]));
}

function empresa()
{
    return \App\Models\Empresa::all()->first();
}

function get_filename_without_extension($file): string
{
    $filenameWithExt = $file->getClientOriginalName();
    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
    return $filename;
}

function nome_byFuncionario($id)
{
    return primeiro_ultimo(Employee::with('pessoas')->find($id)->pessoas->nome);
}

function upload_as($dir, $file, $name)
{
    if ($file != null) {
        deleteDir($dir);
        if (is_file($file)) {
            $upload = $file->storeAs($dir, $name . '.' . $file->extension());
            return $upload;
        }
        return null;
    }
    return null;
}

function __upload_as($directory, $file, $filename)
{
    if ($file != null) {
        $fullFilename = $filename . '.' . $file->extension();
        $path = $file->storeAs($directory, $fullFilename);
        return $path;
    }
    return null;
}

function upload_as_paths($dir, $file, $name)
{
    if ($file != null) {
        if (is_file($file)) {
            $upload = $file->storeAs($dir, $name . '.' . $file->extension());
            return $upload;
        }
        return null;
    }
    return null;
}


function tipoDocumento($id)
{
    return TipoDocumento::all()->find($id);
}

function getContratoProprietario($id)
{
    $contracto = Contracto::all()->where('id', $id)->first();
    if ($contracto->employee_id != null) {
        return $contracto->employees->pessoas->nome;
    }
    if ($contracto->clientes_id != null) {
        return $contracto->clientes->nome;
    }
    if ($contracto->fornecedores_id != null) {
        return $contracto->fornecedores->nome;
    }
    return null;
}


function contar_dias_uteis2($data_inicio, $data_final)
{

    $data_actual = Carbon::parse($data_inicio);
    $data_fim = Carbon::parse($data_final);

    $dias_uteis = 0;
    while ($data_actual <= $data_fim) {
        if ($data_actual->isWeekday())
            $dias_uteis++;
        $data_actual->addDay();
    }
    return $dias_uteis;
}

function contar_dias_uteis($data_inicio, $data_final, $feriados = [])
{

    $data_actual = Carbon::parse($data_inicio);
    $data_fim = Carbon::parse($data_final);

    $dias_uteis = 0;
    while ($data_actual <= $data_fim) {

        //Se não for fim de semana nem Feriado
        if ($data_actual->isWeekday() && !in_array($data_actual->format('Y-m-d'), $feriados))
            $dias_uteis++;
        $data_actual->addDay();
    }
    return $dias_uteis;
}

function getIdade($data_nascimento)
{

    $data = Carbon::parse($data_nascimento);
    $hoje = Carbon::now();
    $idade = $data->diffInYears($hoje);
    return $idade;
}

function entradas_estoque($armarios_id, $farmacos_id, $validade, $data1, $data2)
{
    $estoques = DB::table('entrada_estoques')
        ->where('armarios_id', $armarios_id)
        ->where('farmacos_id', $farmacos_id)
        ->where('validade', $validade)
        ->whereBetween(
            'data',
            [
                Carbon::parse($data1),
                Carbon::parse($data2)
            ]
        )
        ->get();

    if ($estoques->isEmpty())
        return 0;
    return $estoques->sum('qtd');
}

function saidas_estoque($armarios_id, $farmacos_id, $validade, $data1, $data2)
{
    $estoques = DB::table('saida_estoques')
        ->where('armarios_id', $armarios_id)
        ->where('farmacos_id', $farmacos_id)
        ->where('validade', $validade)
        ->whereBetween(
            'data',
            [
                Carbon::parse($data1),
                Carbon::parse($data2)
            ]
        )
        ->get();

    if ($estoques->isEmpty())
        return 0;
    return $estoques->sum('qtd');
}

function estoque_inicial($armarios_id, $farmacos_id, $validade, $data1, $data2)
{
    $estoques = DB::table('entrada_estoques')
        ->where('armarios_id', $armarios_id)
        ->where('farmacos_id', $farmacos_id)
        ->where('validade', $validade)
        ->where('data', '<', $data1)
        ->get();

    $entradas = 0;
    if (!$estoques->isEmpty())
        $entradas = $estoques->sum('qtd');

    $estoques2 = DB::table('saida_estoques')
        ->where('armarios_id', $armarios_id)
        ->where('farmacos_id', $farmacos_id)
        ->where('validade', $validade)
        ->where('data', '<', $data1)
        ->get();

    $saidas = 0;
    if (!$estoques2->isEmpty())
        $saidas = $estoques2->sum('qtd');

    return $entradas - $saidas;
}

function entradas_stock($cabinets_id, $products_id, $sizes_id, $colors_id, $marcas_id, $categories_id, $data1, $data2)
{
    $stocks = DB::table('entrada_stocks')
        ->where('cabinets_id', $cabinets_id)
        ->where('products_id', $products_id)
        ->where('sizes_id', $sizes_id)
        ->where('colors_id', $colors_id)
        ->where('marcas_id', $marcas_id)
        ->where('categories_id', $categories_id)
        ->whereBetween(
            'data',
            [
                Carbon::parse($data1),
                Carbon::parse($data2)
            ]
        )
        ->get();

    if ($stocks->isEmpty())
        return 0;
    return $stocks->sum('qtd');
}

function saidas_stock($cabinets_id, $products_id, $sizes_id, $colors_id, $marcas_id, $categories_id, $data1, $data2)
{
    $stocks = DB::table('saida_stocks')
        ->where('cabinets_id', $cabinets_id)
        ->where('products_id', $products_id)
        ->where('sizes_id', $sizes_id)
        ->where('colors_id', $colors_id)
        ->where('marcas_id', $marcas_id)
        ->where('categories_id', $categories_id)
        ->whereBetween(
            'data',
            [
                Carbon::parse($data1),
                Carbon::parse($data2)
            ]
        )
        ->get();

    if ($stocks->isEmpty())
        return 0;
    return $stocks->sum('qtd');
}

function stock_inicial($cabinets_id, $products_id, $sizes_id, $colors_id, $marcas_id, $categories_id, $data1, $data2)
{
    $stocks = DB::table('entrada_stocks')
        ->where('cabinets_id', $cabinets_id)
        ->where('products_id', $products_id)
        ->where('sizes_id', $sizes_id)
        ->where('colors_id', $colors_id)
        ->where('marcas_id', $marcas_id)
        ->where('categories_id', $categories_id)
        ->where('created_at', '<', $data1)
        ->get();

    $entradas = 0;
    if (!$stocks->isEmpty())
        $entradas = $stocks->sum('qtd');

    $stocks2 = DB::table('saida_stocks')
        ->where('cabinets_id', $cabinets_id)
        ->where('products_id', $products_id)
        ->where('sizes_id', $sizes_id)
        ->where('colors_id', $colors_id)
        ->where('marcas_id', $marcas_id)
        ->where('categories_id', $categories_id)
        ->where('created_at', '<', $data1)
        ->get();

    $saidas = 0;
    if (!$stocks2->isEmpty())
        $saidas = $stocks2->sum('qtd');

    return $entradas - $saidas;
}

function status_order($orders_id)
{
    $order = DB::table('orders')
        ->join('orders_status', 'orders_status.orders_id', 'orders.id')
        ->join('orders_products', 'orders_products.orders_id', 'orders.id')
        ->join('statuses', 'statuses.id', 'orders_status.status_id')
        ->leftJoin('users', 'users.id', 'orders_status.users_id')
        ->where('orders.id', $orders_id)
        ->get([
            '*',
            'orders.id as id',
            'statuses.status as status',
        ])
        ->last();
    return $order;
}

function getStock_actual($products_id, $sizes_id, $colors_id, $marcas_id, $categories_id, $validade = null, $cabinets_id = 1)
{
    $stock = Stock::all()
        ->where('cabinets_id', $cabinets_id)
        ->where('products_id', $products_id)
        ->where('sizes_id', $sizes_id)
        ->where('marcas_id', $marcas_id)
        ->where('categories_id', $categories_id)
        ->where('validade', $validade)
        ->where('colors_id', $colors_id);

    $resultado = 0;
    if (!$stock->isEmpty())
        $resultado = $stock->first()->qtd;

    return $resultado;
}

function actualizar_stock($qtd, $products_id, $sizes_id, $colors_id, $marcas_id, $categories_id, $validade = null, $cabinets_id = 1)
{
    $tock = Stock::all()
        ->where('products_id', $products_id)
        ->where('cabinets_id', $cabinets_id)
        ->where('sizes_id', $sizes_id)
        ->where('marcas_id', $marcas_id)
        ->where('categories_id', $categories_id)
        ->where('validade', $validade)
        ->where('colors_id', $colors_id)->last();

    $qtd_stock = $tock == null ? 0 : $tock->qtd;

    if ($qtd <= $qtd_stock) {
        $dados = [
            'products_id' => $products_id,
            'cabinets_id' => $cabinets_id,
            'sizes_id' => $sizes_id,
            'colors_id' => $colors_id,
            'marcas_id' => $marcas_id,
            'categories_id' => $categories_id,
            'validade' => $validade,
            'qtd' => $qtd,
            'data' => now(),
            'hora' => now(),
            'obs' => 'Pedido de Stock Logistica',
            'users_id' => auth()->id(),
            'motivo' => 'Pedido de Stock Logistica',
        ];
        $saida = SaidaStock::create($dados);
    }
}


function salvar_historico($pedido_pagamentos_id, $status_id = 1, $obs = "Solicitação")
{

    $historico = HistoricoPagamento::create([
        'data' => now(),
        'obs' => $obs,
        'pedido_pagamentos_id' => $pedido_pagamentos_id,
        'users_id' => \auth()->id(),
        'status_pagamentos_id' => $status_id,
    ]);
    return $historico;
}

function status_pedido_pagamentos($pagamento)
{
    return HistoricoPagamento::all()
        ->where('pedido_pagamentos_id', $pagamento->id)->last();
}

function remover_acentos($str)
{
    $caracteres_sem_acento = array(
        'Š' => 'S', 'š' => 's', 'Ð' => 'Dj', 'Â' => 'Z', 'Â' => 'z', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A',
        'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I',
        'Ï' => 'I', 'Ñ' => 'N', 'Å' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U',
        'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a',
        'å' => 'a', 'æ' => 'a', 'ç' => 'c', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i',
        'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'Å' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o', 'ù' => 'u',
        'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ý' => 'y', 'ý' => 'y', 'þ' => 'b', 'ÿ' => 'y', 'ƒ' => 'f',
        'Ä' => 'a', 'î' => 'i', 'â' => 'a', 'È' => 's', 'È' => 't', 'Ä' => 'A', 'Î' => 'I', 'Â' => 'A', 'È' => 'S', 'È' => 'T',
    );

    return strtr($str, $caracteres_sem_acento);
}


// Salários
function mes($mes_inteiro)
{
    $meses = array(
        1 => 'Janeiro',
        2 => 'Fevereiro',
        3 => 'Março',
        4 => 'Abril',
        5 => 'Maio',
        6 => 'Junho',
        7 => 'Julho',
        8 => 'Agosto',
        9 => 'Setembro',
        10 => 'Outubro',
        11 => 'Novembro',
        12 => 'Dezembro'
    );
    foreach ($meses as $key => $mes) {
        if ($key == $mes_inteiro)
            return $mes;
    }
    return null;
}

function faltas($id, $mes, $ano)
{
    $ef = Efectividade::query()
        ->where('employees_id', $id)
        ->whereYear('inicio', $ano)
        ->where('mes', $mes)
        ->get();

    $fj = 0;
    $fi = 0;
    $dias = 0;
    $horas = 0;
    $ferias = 0;
    foreach ($ef as $item) {
        if ($item->hora) {
            // TODO: Verificar se as horas estão corretas e arredondadas
            $horas += calcular_horas($item->hora_inicio, $item->hora_fim);
        } else {
            $dias += $item->dias;
            if ($item->justificado) {
                $fj += $item->dias;
            } else {
                $fi += $item->dias;
            }
            $ferias += $item->faltas_ferias;
        }
    }
    $dados = [
        'fj' => $fj,
        'fi' => $fi,
        'dias' => 22 - $dias,
        'horas' => $horas,
        'ferias' => $ferias,
    ];
    return $dados;
}

function irt(
    $salario,
    $inss,
    $sub_ferias,
    $sub_natal,
    $sub_bonus_extra,
    $sub_bonus_driver,
    $sub_desempenho,
    $sub_reconhecimento,
    $sub_renda,
    $sub_mergulho,
    $sub_turno,
    $sub_off_driver,
    $sub_isolamento,
    $sub_offshore,
    $outros_sub,
    $dias_extra,
    $sub_trabalho_noturno,
    $sub_alimenmtacao,
    $sub_transporte,
    $abano,
    $sub_proporcional_ferias,
    $sub_proporcional_natal,
    $desc_faltas, $base
)
{
//    $mc = $salario - $inss - $abano + $sub_ferias + $sub_natal + $sub_bonus_extra +
//        $sub_bonus_driver + $sub_desempenho + $sub_reconhecimento +
//        $sub_renda + $sub_mergulho +
//        $sub_turno + $sub_off_driver
//        + $sub_isolamento + $sub_offshore +
//        $outros_sub + $dias_extra + $sub_trabalho_noturno
//        + apto_irt($sub_alimenmtacao) + apto_irt($sub_transporte) - $desc_faltas +
//        $sub_proporcional_ferias + $sub_proporcional_natal;

    $mc = $base;

    $taxa = 0;
    $parcela = 0;
    $sobExcesso = 0;

    //sobre excesso
    if ($mc <= 100000) {
        return 0;
    } else {
        //sobre excesso
        if ($mc > 100000 && $mc <= 150000) {
            $sobExcesso = 100001;
            $parcela = 0;
            $taxa = 0.13;
        } else if ($mc > 150000 && $mc <= 200000) {
            $sobExcesso = 150001;
            $parcela = 12500;
            $taxa = 0.16;
        } else if ($mc > 200000 && $mc <= 300000) {
            $sobExcesso = 200001;
            $parcela = 31250;
            $taxa = 0.18;
        } else if ($mc > 300000 && $mc <= 500000) {
            $sobExcesso = 300001;
            $parcela = 49250;
            $taxa = 0.19;
        } else if ($mc > 500000 && $mc <= 1000000) {
            $sobExcesso = 500001;
            $parcela = 87250;
            $taxa = 0.20;
        } else if ($mc > 1000000 && $mc <= 1500000) {
            $sobExcesso = 1000001;
            $parcela = 187249;
            $taxa = 0.21;
        } else if ($mc > 1500000 && $mc <= 2000000) {
            $sobExcesso = 1500001;
            $parcela = 292249;
            $taxa = 0.22;
        } else if ($mc > 2000000 && $mc <= 2500000) {
            $sobExcesso = 2000001;
            $parcela = 402249;
            $taxa = 0.23;
        } else if ($mc > 2500000 && $mc <= 5000000) {
            $sobExcesso = 2500001;
            $parcela = 517249;
            $taxa = 0.24;
        } else if ($mc > 5000000 && $mc <= 10000000) {
            $sobExcesso = 5000001;
            $parcela = 1117249;
            $taxa = 0.245;
        } else if ($mc > 10000000) {
            $sobExcesso = 10000001;
            $parcela = 2342248;
            $taxa = 0.25;
        }

        return (($mc - $sobExcesso) * $taxa) + $parcela;
    }
}

// function imposto($id)
// {
//     return \App\Models\Imposto::all()->find($id);
// }

function apto_irt($valor)
{
    if ($valor > 30000)
        return $valor - 30000;
    else
        return 0;
}

function isSocio($employee)
{
    if ($employee->categoria != null) {
        if ($employee->categoria->categoria == 'Sócio') {
            return true;
        }
    }
}

function subsidios($employees_id)
{
    return SubsidiosEmployee::all()->where('employees_id', $employees_id)->first();
}

function total_subsidios($subsidios)
{
    $total = 0;
    foreach ($subsidios as $subsidio) {
        $total += floatval($subsidio);
    }
    return $total;
}

function total_descontos($descontos)
{
    $total = 0;
    foreach ($descontos as $desconto) {
        $total += $desconto;
    }
    return $total;
}

function inss($employees_id, $subsidios, $desc_faltas)
{
    $employee = Employee::all()->find($employees_id);
    if (isSocio($employee))
        return 0;

    return 0.03 * ($employee->salario + total_subsidios($subsidios) - $subsidios['abono'] - $subsidios['ferias'] - $subsidios['sub_proporcional_ferias'] - $desc_faltas);
}

function inss_reformado($employees_id, $subsidios, $desc_faltas)
{
    $employee = Employee::all()->find($employees_id);
    if (isSocio($employee))
        return 0;

    return 0.08 * ($employee->salario + total_subsidios($subsidios) - $subsidios['abono'] - $subsidios['ferias'] - $subsidios['sub_proporcional_ferias'] - $desc_faltas);
}

function inss_empresa($employees_id, $subsidios, $desc_faltas)
{
    $employee = Employee::all()->find($employees_id);
    if (isSocio($employee))
        return 0;

    return 0.08 * ($employee->salario + total_subsidios($subsidios) - $subsidios['abono'] - $subsidios['ferias'] - $subsidios['sub_proporcional_ferias'] - $desc_faltas);
}

function irt_salario($employees_id, $inss, $subsidios, $desc_faltas, $pagar = true)
{
    $employee = Employee::all()->find($employees_id);
    $base = base_tributavel_irt($employee->salario, $inss, $subsidios, $desc_faltas);
    if ($pagar) {
        if (isSocio($employee))
            return $employee->salario * 0.065;
        else
            return irt(
                $employee->salario,
                $inss,
                $subsidios['ferias'],
                $subsidios['natal'],
                $subsidios['bonus_extra'],
                $subsidios['bonus_posisao_driver'],
                $subsidios['desempenho_mensal'],
                $subsidios['sub_reconhecimento'],
                $subsidios['sub_renda'],
                $subsidios['sub_mergulho'],
                $subsidios['sub_turno'],
                $subsidios['sub_offshore_driver'],
                $subsidios['sub_isolamento'],
                $subsidios['sub_offshore_hs'],
                $subsidios['outros_sub'],
                $subsidios['dias_extra'],
                $subsidios['sub_trabalho_noturno'],
                $subsidios['alimentacao'],
                $subsidios['transporte'],
                $subsidios['abono'],
                $subsidios['sub_proporcional_ferias'],
                $subsidios['sub_proporcional_natal'],
                $desc_faltas,
                $base
            );
    } else
        return 0;
}

function salario_iliquido($id)
{
    $sal = new Salario();
    $salario = $sal->dados_salarios()
        ->where('salarios.id', $id)
        ->get([
            '*',
            'salarios.id as id',
            'salarios.inss as inss',
        ])
        ->first();

    return $salario->sb +
        $salario->abono +
        $salario->alimentacao +
        $salario->transporte +
        $salario->sub_ferias +
        $salario->sub_natal +
        $salario->bonus_posisao_driver +
        $salario->bonus_extra +
        $salario->sub_reconhecimento +
        $salario->sub_desempenho_mensal +
        $salario->sub_renda +
        $salario->sub_mergulho +
        $salario->sub_turno +
        $salario->sub_offshore_driver +
        $salario->sub_isolamento +
        $salario->sub_offshore_hs +
        $salario->dias_extra +
        $salario->sub_trabalho_noturno +
        $salario->outros_sub +
        $salario->sub_proporcional_ferias +
        $salario->sub_proporcional_natal +
        $salario->diferenca_salarial;
}

function descontos($id)
{
    $sal = new Salario();
    $salario = $sal->dados_salarios()
        ->where('salarios.id', $id)
        ->get([
            '*',
            'salarios.id as id',
            'salarios.inss as inss',
        ])
        ->first();

    return $salario->inss +
        $salario->irt +
        $salario->faltas +
        $salario->descontos_transporte +
        $salario->descontos_alimentacao +
        $salario->outros_desc;
}

function salario_liquido($id)
{
    return salario_iliquido($id) - descontos($id);
}

function salario_retido($id, $ano)
{
    $sal = new Salario();
    $salarios = $sal->dados_salarios()
        ->where('employees.id', $id)
        ->where('ano', $ano)->get([
            '*',
            'salarios.id as id',
            'salarios.inss as inss',
        ]);
    $total = 0;
    foreach ($salarios as $salario) {
        $total += salario_liquido($salario->id);
    }
    return $total;
}

function irt_retido($id, $ano)
{
    $sal = new Salario();
    return $sal->dados_salarios()
        ->where('employees.id', $id)
        ->where('ano', $ano)
        ->sum('irt');
}

function converter_moeda()
{
    $url = "https://economia.awesomeapi.com.br/json/last/USD-AOA";

    $get = file_get_contents($url);
    $get = json_decode($get, true);

    return floatval($get['USDAOA']['bid']);
}

function salario_dolar($id)
{
    return salario_liquido($id) / converter_moeda();
}

function get_qtd_dependents($id)
{
    $dependentes = Dependente::all()->where('employees_id', $id);
    return $dependentes->count();
}


function dencontos_pessoais($id, $salarios, $request)
{
    $total = 0;
    $discontos = Adiantamento::all()->where('employees_id', $id);
    if ($discontos->isEmpty())
        return 0;
    else {
        foreach ($discontos as $disconto) {
            $data_pedido = Carbon::parse($disconto->data_inicio_pagamento);
            $data_atual = Carbon::now();
            if (($data_atual >= $data_pedido
                    && $request->mes >= $data_pedido->month
                    && $request->ano >= $data_pedido->year) || $disconto->parcelas_pagas > 0) {
                if ($disconto->parcelas > $disconto->parcelas_pagas) {
                    $total += $disconto->valor_mensal;
                    $disconto->parcelas_pagas += 1;
                    $disconto->save();
                    if ($disconto->adiantamento_motivos_id == 1)
                        $salarios->adiantamento_salarial = $disconto->valor_mensal;

                    if ($disconto->adiantamento_motivos_id == 2)
                        $salarios->adiantamento_ferias = $disconto->valor_mensal;

                    if ($disconto->adiantamento_motivos_id == 3)
                        $salarios->adiantamento_decimo_terceiro = $disconto->valor_mensal;

                    if ($disconto->adiantamento_motivos_id == 4)
                        $salarios->desconto_seguro = $disconto->valor_mensal;

                    if ($disconto->adiantamento_motivos_id == 5)
                        $salarios->credito_viatura = $disconto->valor_mensal;

                    if ($disconto->adiantamento_motivos_id == 6)
                        $salarios->desconto_dependentes = $disconto->valor_mensal;

                    if ($disconto->adiantamento_motivos_id == 7)
                        $salarios->medida_disciplinar = $disconto->valor_mensal;

                    if ($disconto->adiantamento_motivos_id == 8)
                        $salarios->descontos_alimentacao = $disconto->valor_mensal;

                    if ($disconto->adiantamento_motivos_id == 9)
                        $salarios->descontos_transporte = $disconto->valor_mensal;

                    $salarios->save();
                }
            } else {
                $total += 0;
            }
        }
        return $total;
    }
}

function getFaltasPorFerias($id, $mes, $ano)
{
    $faltas = \App\Models\Feria::query()
        ->where('employees_id', $id)
        ->where('ano', $ano)
        ->whereMonth('inicio', $mes)
        ->where('status_requests_id', 1)
        ->get()->last();

    return $faltas == null ? 0 : contar_dias_uteis($faltas->inicio, $faltas->final);
}

function getRequestFloat(Request $request): Request
{
    $request['retencao'] = str_replace(',', '.', str_replace('.', '', str_replace('AOA', '', $request->retencao)));
    $request['iva'] = str_replace(',', '.', str_replace('.', '', str_replace('AOA', '', $request->iva)));
    $request['valor'] = str_replace(',', '.', str_replace('.', '', str_replace('AOA', '', $request->valor)));
    return $request;
}

function getFloat($dado): array|string
{
    return doubleval(str_replace(',', '.', str_replace('.', '', str_replace('AOA', '', $dado))));
}

function getFloat2($dado): array|string
{
    return doubleval(str_replace(',', '.', str_replace('.', '', str_replace('', '', $dado))));
}


// TODO: Trabalhar e verificar nas funções que compõem a parte do mapa de salários
// Trabalhando na parte do mapa de salários
function excesso_sub($sub)
{
    if ($sub > 30000) {
        return $sub - 30000;
    }
    return 0;
}

function add_excesso_normal($sub)
{
    $nao_taxado = 0;
    $taxado = 0;

    if ($sub > 30000) {
        $nao_taxado = 30000;
        $taxado = $sub - 30000;
    } else {
        $nao_taxado = $sub;
    }

    return [
        'nao_taxado' => $nao_taxado,
        'taxado' => $taxado
    ];
}

function base_tributavel_irt($salario, $inss, $subsidios, $desc_faltas)
{
    return $salario + total_subsidios($subsidios) - $inss - $subsidios['abono']
        - add_excesso_normal($subsidios['alimentacao'])['nao_taxado']
        - add_excesso_normal($subsidios['transporte'])['nao_taxado']
        - $desc_faltas;
}

function base_tributavel_inss($salario, $subsidios, $desc_faltas)
{
    return $salario + total_subsidios($subsidios) - $subsidios['abono'] - $subsidios['ferias'] - $subsidios['sub_proporcional_ferias'] - $desc_faltas;
}

function total_excesso_sub($subsidios)
{
    return excesso_sub($subsidios['alimentacao']) + excesso_sub($subsidios['transporte']);
}

function total_sub_sujeitos_irt($subsidios)
{
    return total_subsidios($subsidios) - $subsidios['abono'] - apto_irt($subsidios['alimentacao']) - apto_irt($subsidios['transporte']);
}

function total_sub_n_sujeitos_inss($subsidios)
{
    return $subsidios['abono'] + $subsidios['ferias'] + $subsidios['sub_proporcional_ferias'];
}

function ferias_pago($employees_id, $ano)
{
    $sal = new Salario();
    $salarios = $sal->dados_salarios()
        ->where('salarios.employees_id', $employees_id)
        ->where('ano', $ano)->get([
            '*',
            'salarios.id as id',
        ]);
    $total = 0;
    foreach ($salarios as $salario) {
        if ($salario->sub_ferias > 0)
            $total += 1;
        if ($total > 0)
            return true;
    }
    return null;
}

function natal_pago($employees_id, $ano)
{
    $sal = new Salario();
    $salarios = $sal->dados_salarios()
        ->where('salarios.employees_id', $employees_id)
        ->where('ano', $ano)->get([
            '*',
            'salarios.id as id',
        ]);
    $total = 0;
    foreach ($salarios as $salario) {
        if ($salario->sub_natal > 0)
            $total += 1;
        if ($total > 0)
            return true;
    }
    return null;
}

function create_object_by_collection(Collection $list, array $props): array
{
    $arr = [];
    foreach ($list as $item) {
        $object = new \stdClass();
        foreach ($props as $prop) $object->$prop = $item[$prop];
        $arr[] = $object;
    }
    return $arr;
}

function saldo_anterior_factura($fornecedores_id, $data1)
{
    //##########################################
    #Consulta para gerar Saldo anterior

    if ($fornecedores_id == "%") {
        $pagamentos = DB::table('pagamentos')
//            ->where('fornecedores_id', $fornecedores_id)
            ->where('data', '<', $data1)
            ->get();

        $saldo_anterior = ($pagamentos->sum('valor'));

        $facturas = DB::table('invoices')
//            ->where('fornecedores_id', $fornecedores_id)
            ->where('data', '<', $data1)
            ->get();

        $saldo_anterior += ($facturas->sum('valor'));
    } else {
        $pagamentos = DB::table('pagamentos')
            ->where('fornecedores_id', $fornecedores_id)
            ->where('data', '<', $data1)
            ->get();

        $saldo_anterior = ($pagamentos->sum('valor'));

        $facturas = DB::table('invoices')
            ->where('fornecedores_id', $fornecedores_id)
            ->where('data', '<', $data1)
            ->get();

        $saldo_anterior += ($facturas->sum('valor'));
    }

    return $saldo_anterior;
}

// função para calcular horas
function calcular_horas($hora1, $hora2)
{
    $hora_1 = explode(":", $hora1 ?? "00:00");
    $hora_2 = explode(":", $hora2 ?? "00:00");

    $hora1 = intval($hora_1[0]) * 60 + intval($hora_1[1]);
    $hora2 = intval($hora_2[0]) * 60 + intval($hora_2[1]);

    $diferenca = $hora2 - $hora1;

    $horas = floor($diferenca / 60);
    $minutos = $diferenca % 60;

    return $horas;
}


function calcular_horas2($hora1, $hora2, $data1 = null, $data2 = null)
{
    $dias = 1; // Assume pelo menos um dia útil

    // Se datas forem passadas, calcula os dias úteis
    if ($data1 != null && $data2 != null) {
        $dias = contar_dias_uteis($data1, $data2);
    }

    // Separa horas e minutos das strings
    list($h1, $m1) = explode(":", $hora1 ?? "00:00");
    list($h2, $m2) = explode(":", $hora2 ?? "00:00");

    // Converte para minutos totais
    $minutos1 = intval($h1) * 60 + intval($m1);
    $minutos2 = intval($h2) * 60 + intval($m2);

    // Se a segunda hora for menor que a primeira, assume que passou da meia-noite
    if ($minutos2 < $minutos1) {
        $minutos2 += 24 * 60; // Adiciona um dia completo em minutos
    }

    // Diferença total em minutos
    $diferenca = $minutos2 - $minutos1;

    // Multiplica pelo número de dias úteis
    $diferenca_total = $diferenca * $dias;

    // Calcula horas e minutos
    $horas = floor($diferenca_total / 60);
    $minutos = $diferenca_total % 60;

    return sprintf("%02d:%02d", $horas, $minutos);
}

function calcular_horas_trabalho($hora1, $hora2, $data1 = null, $data2 = null)
{
    $dias = 1; // Assume pelo menos um dia útil

    // Se datas forem passadas, calcula os dias úteis
    if ($data1 != null && $data2 != null) {
        $dias = contar_dias_uteis($data1, $data2);
    }

    // Define o horário de trabalho (08:00 às 16:00)
    $inicio_expediente = 8 * 60;  // 08:00 em minutos
    $fim_expediente = 16 * 60;    // 16:00 em minutos

    // Separa horas e minutos das strings
    list($h1, $m1) = explode(":", $hora1 ?? "00:00");
    list($h2, $m2) = explode(":", $hora2 ?? "00:00");

    // Converte para minutos totais
    $minutos1 = intval($h1) * 60 + intval($m1);
    $minutos2 = intval($h2) * 60 + intval($m2);

    // Garante que os horários estejam dentro do expediente
    $minutos1 = max($inicio_expediente, min($minutos1, $fim_expediente));
    $minutos2 = max($inicio_expediente, min($minutos2, $fim_expediente));

    // Se a segunda hora for menor que a primeira, não pode considerar tempo negativo
    if ($minutos2 < $minutos1) {
        return "00:00"; // Retorna 0 caso o intervalo não esteja correto
    }

    // Calcula a diferença total em minutos dentro do expediente
    $diferenca = $minutos2 - $minutos1;

    // Multiplica pelo número de dias úteis
    $diferenca_total = $diferenca * $dias;

    // Calcula horas e minutos finais
    $horas = floor($diferenca_total / 60);
    $minutos = $diferenca_total % 60;

    return sprintf("%02d:%02d", $horas, $minutos);
}


function getItensByInvoice($invoice_id)
{
    $valor = 0;
    $iva = 0;
    $retencao = 0;
    $total = 0;
    $base_tributavel = 0;

    $itens = Iten::where('invoices_id', $invoice_id)->get();

    foreach ($itens as $item) {
        if ($item->retencao == 1) {
            $retencao += ($item->preco_unitario * floatval($item->qtd)) * 0.065;
        }
        $iva += ($item->preco_unitario * floatval($item->qtd)) * $item->iva;
        $valor += $item->preco_unitario * floatval($item->qtd);
        if ($item->iva > 0) {
            $base_tributavel += $item->preco_unitario * floatval($item->qtd);
        }
    }
    // TODO: Foi retirado a retenção, segundo o Sr. Domingos a retenção só é usada no pagamento
    $total = $valor + $iva;

//    dd($total, $iva, $valor, $invoice_id);

    return [
        'valor' => $valor,
        'iva' => $iva,
        'retencao' => $retencao,
        'total' => $total,
        'itens' => $itens,
        'base_tributavel' => $base_tributavel
    ];
}

function diferencaDatasParaProporcionalidade($dataFornecida)
{
    // Converter a data fornecida para um objeto Carbon
    $dataFornecida = Carbon::parse($dataFornecida);

    // Obter a data atual
    $dataAtual = Carbon::now();

    return [
        'dias' => $dataAtual->diffInDays($dataFornecida),
        'meses' => $dataAtual->diffInMonths($dataFornecida),
        'anos' => $dataAtual->diffInYears($dataFornecida),
    ];
}

function get_logo_by_company()
{
    $empresa = \App\Models\Empresa::all()->find(session()->get('empresas_id'));
    if ($empresa->id == 1) {
        return [
            'logotipo' => 'img/logo_operatec_novo.jpg',
            'nif' => $empresa->nif,
            'nome' => strtoupper($empresa->nome),
        ];
    } else {
        return [
            'logotipo' => 'img/omitc_log.png',
            'nif' => $empresa->nif,
            'nome' => strtoupper($empresa->nome),
        ];
    }
}

function getYears(): array
{
    $years = [];
    for ($ano = date('Y') - 1; $ano <= date('Y') + 1; $ano++) {
        $years[] = $ano;
    }
    return $years;
}

function getMeses(): array
{
    return [
        1 => 'Janeiro',
        2 => 'Fevereiro',
        3 => 'Março',
        4 => 'Abril',
        5 => 'Maio',
        6 => 'Junho',
        7 => 'Julho',
        8 => 'Agosto',
        9 => 'Setembro',
        10 => 'Outubro',
        11 => 'Novembro',
        12 => 'Dezembro',
    ];
}
