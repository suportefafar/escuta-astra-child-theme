<?php

function get_submission_by_id( $id ) {

    global $wpdb;

    $table_name    = $wpdb->prefix.'fafar_cf7crud_submissions';
    $query = "SELECT * FROM `" . $table_name . "` WHERE id = '" . $id . "'";

    $submissions = $wpdb->get_results( $query );

    if ( empty( $submissions ) ) {
        return array();
    }

    $submission_decoded = (array) json_decode( $submissions[0]->data );

    $submission_decoded["id"] = $submissions[0]->id;
    $submission_decoded["form_id"]       = $submissions[0]->form_id;
    $submission_decoded["object_name"]     = $submissions[0]->object_name;
    $submission_decoded["is_active"]     = $submissions[0]->is_active;
    $submission_decoded["updated_at"]     = $submissions[0]->updated_at;
    $submission_decoded["created_at"]    = $submissions[0]->created_at;

    return $submission_decoded;

}

function get_acolhidos( $nome = "" ) {

    global $wpdb;

    $table_name = $wpdb->prefix . 'fafar_cf7crud_submissions';
    $query      = 'SELECT * FROM `' . $table_name . '` WHERE `object_name` = "acolhido" ';

    $submissions = $wpdb->get_results( $query );

    if ( empty( $submissions ) ) {
        return array(); 
    }

    $acolhidos = array();

    foreach ( $submissions as $submission ) {

        //$submission_decoded = unserialize( $submission->data );
        $submission_decoded = (array) json_decode( $submission->data );


        $submission_decoded["id"]            = $submission->id;
        $submission_decoded["form_id"]       = $submission->form_id;
        $submission_decoded["updated_at"]    = $submission->updated_at;
        $submission_decoded["created_at"]    = $submission->created_at;

		if ( $nome == '' ) {

        	array_push( $acolhidos, $submission_decoded );

		} else {

			
			if ( str_contains( strtolower( $submission_decoded["nome"] ), strtolower( $nome ) ) )
				array_push( $acolhidos, $submission_decoded );

		}
    }

    return $acolhidos;

}

function get_escutas( $q = "" ) {

    global $wpdb;

    $table_name = $wpdb->prefix . 'fafar_cf7crud_submissions';
    $query      = 'SELECT * FROM `' . $table_name . '` WHERE `object_name` = "escuta" ';

    $submissions = $wpdb->get_results( $query );

    if ( empty( $submissions ) ) {
        return array(); 
    }

    $acolhidos = array();

    foreach ( $submissions as $submission ) {

        //$submission_decoded = unserialize( $submission->data );
        $submission_decoded = (array) json_decode( $submission->data );

        $submission_decoded["id"] = $submission->id;
        $submission_decoded["form_id"]       = $submission->form_id;
        $submission_decoded["updated_at"]    = $submission->updated_at;
        $submission_decoded["created_at"]    = $submission->created_at;

        array_push( $acolhidos, $submission_decoded );

    }

    return $acolhidos;

}


function get_hero_error( $text, $code = 1 ) {

    $hero_image = 'error-hero.png';

    if( $code == 2 )
        $hero_image = 'not-found-hero.png';


    return '<div class="d-flex flex-column justify-content-center align-items-center gap-3">
			<img width="256" src="' . get_stylesheet_directory_uri() . '/assets/img/' . $hero_image . '" alt="[' . $code . '] Solicitação incorreta" />
			<h6>' . $text . '</h6>
		</div>';
}


function gerar_lista_acolhidos() {

    $upload_dir    = wp_upload_dir();
    $cfdb7_dir_url = $upload_dir['baseurl'] . '/fafar-cf7crud-uploads';

    $acolhidos = array();
    if( isset( $_GET['q-nome'] ) ) $acolhidos = get_acolhidos( $_GET['q-nome'] );
    else $acolhidos = get_acolhidos();

    $conteudo_lista = '';
    if ( empty( $acolhidos ) )
        $conteudo_lista = get_hero_error( 'Nenhum acolhido encontrado', 2 );

	


    foreach ( $acolhidos as $acolhido ) {

		$caminho_foto = 
			isset( $acolhido['fafar-cf7crud-file-foto'] ) ? 
			$cfdb7_dir_url . '/' . $acolhido['fafar-cf7crud-file-foto'] : 
			get_stylesheet_directory_uri() . '/assets/img/user-icon.svg';

        $conteudo_lista .=	'<div class="lista-item-container">
                        <div class="lista-item-img-container">
							<img
								src="' . $caminho_foto . '"
								alt="Foto Acolhido"
								width="64" 
								height="64"
							/>
                        </div>
                        <div class="lista-item-info-container">
                            <h4> <a class="text-decoration-none" href="/perfil?id=' . $acolhido['id'] . '">' . ( empty( $acolhido['nome-social'] ) ? $acolhido['nome'] : $acolhido['nome-social'] ) . '</a> </h4>
                                
                            <div class="d-flex justify-content-between">
                                <small class="text-body-secondary"> ' . $acolhido['tipo-ligacao-institucional'][0] . ' </small>
                                    
                                <small class="text-body-secondary"> ' . $acolhido['telefone'] . ' </small>
                                    
                                <small class="text-body-secondary"> ' . $acolhido['email'] . ' </small>
                            </div>
                        </div>
                    </div>';

    }

    $output = '<div class="d-flex flex-column gap-4">';
    $output .= '<div class="d-flex justify-content-center">
                    <div class="search-input-container-acolhidos">
                        <form method="GET" action="./acolhidos" class="search-input-container">
                            <input
                                placeholder="Pesquisar..."
                                name="q-nome"
                                type="text"
                            />
                            <button type="submit">
                                <i class="bi bi-search" aria-label="Ícone pesquisa" title="Ícone relógio"></i>
                            </button>
                        </form>
                    </div>
                </div>';
    $output .= '<div class="d-flex justify-content-between align-items-center">';
    $output .=      '<h5 class="m-0 p-0">Lista dos acolhidos:</h5>';
    $output .=      '<a class="fafar-aw-clean-button has-ast-global-color-0-color has-ast-global-color-5-background-color" 
                        href="/novo-acolhido">';
    $output .=	    '<i class="bi bi-plus-circle" aria-label="Ícone adicionar" title="Ícone adicionar"></i>';
    $output .=      '<span>Acolhido</span>';
    $output .=	    '</a>';
    $output .= '</div>';
    $output .= $conteudo_lista;
    $output .= '</div>';

    return $output;
}

/**
 * ---------------------------------------------------------------------------------------------------------------------
**/

 function gerar_perfil_acolhido() {

	if ( !isset( $_GET['id'] ) ) {
		return "<div class='d-flex flex-column justify-content-center align-items-center gap-3'>
					<img width='256' src='" . get_stylesheet_directory_uri() . "/assets/img/error-hero.png' alt='[001] Solicitação incorreta' />
					<h6>[001] Solicitação incorreta</h6>
				</div>"; 
	}
    
	$id = $_GET['id'];

    $acolhido = get_submission_by_id( $id );

	if ( ! $acolhido ) {
		return "<div class='d-flex flex-column justify-content-center align-items-center gap-3'>
					<img width='256' src='" . get_stylesheet_directory_uri() . "/assets/img/not-found-hero.png' alt='[002] Nenhum acolhido encontrado' />
					<h6>[002] Nenhum acolhido encontrado</h6>
				</div>"; 
	}

	// TODO NOT USED
	$upload_dir    = wp_upload_dir();
	$cfdb7_dir_url = $upload_dir['baseurl'] . '/fafar-cf7crud-uploads';
	$rm_underscore = apply_filters('cfdb7_remove_underscore_data', true);
	
	$linhas_informacao_tabela = "";

	foreach ($acolhido as $chave => $valor) {

		// Configurando '$chave'
		$matches = array();
		$chave   = esc_html( $chave );
	
		if ( str_contains( $chave, 'fafar-cf7crud-file' ) ) continue;
		if ( $chave == 'submission-id' || $chave == 'id' ) continue;
		if ( $chave == 'form-id' || $chave == 'form_id' ) continue;
		if ( $chave == 'created-at' || $chave == 'created_at' ) continue;
		if ( $chave == 'updated-at' || $chave == 'updated_at' ) continue;
		
		if ( strpos( $chave, 'fafarcf7crudfile' ) !== false ) $chave = str_replace( 'fafarcf7crudfile', '', $chave );
		if( $rm_underscore ) preg_match('/^_.*$/m', $chave, $matches);
		if( ! empty($matches[0]) ) continue;

		$chave_formatada = str_replace("-", " ", $chave);
		$chave_formatada = str_replace("_", " ", $chave);
		$chave_formatada = ucwords( $chave_formatada );


		// Configurando '$valor'
		if( empty( $valor ) ) continue;

		if ( is_array( $valor ) ) $valor = $valor[0];

		$phone_matchs = array();
		preg_match( '/\([0-9][0-9]\) 9?[0-9]+-[0-9]+/', $valor, $phone_matchs);
		if( ! empty( $phone_matchs ) ) {
			$valor = '<a href="tel:' . $valor . '" target="_blank">' . $valor . '</a>';
		}

		$email_matchs = array();
		if( filter_var( $valor, FILTER_VALIDATE_EMAIL ) ) {
			$valor = '<a href="mail:' . $valor . '" target="_blank">' . $valor . '</a>';
		}

		$file_matchs = array();
		preg_match( '/\.[a-z\d]?[a-z\d][a-z\d][a-z\d]$/', $valor, $file_matchs, PREG_OFFSET_CAPTURE );
		if( ! empty( $file_matchs ) ) {
			$valor = '<a href="' . $cfdb7_dir_url . "/" . $valor . '" target="_blank">' . $valor . '</a>';
		}
		
		$estilo_para_info_binaria = '';
		if( strtolower( $valor ) === 'sim' ) $estilo_para_info_binaria = 'texto-resposta-afirmativa';
		else if( strtolower( $valor ) === 'nao' || strtolower( $valor ) === 'não' ) $estilo_para_info_binaria = 'texto-resposta-negativa';

		if ( $chave === 'registro-academico' ) {
			$valor = '<a href="https://sistemas.ufmg.br/configuracao-curricular/integralizacao/emitirextrato/emitirNovoExtrato.seam?bkmk=" target="_blank">' . $valor . '</a>';
		}
		
		$linhas_informacao_tabela .= '<tr class="linha-info-perfil" data-chave-info="' . $chave . '">';
		$linhas_informacao_tabela .= 	'<td class="w-50">' . $chave_formatada . '</td>';
		$linhas_informacao_tabela .= 	'<td contenteditable="false" class="fw-bold info-valor ' . $estilo_para_info_binaria . '">
											<div class="d-flex flex-column">' . $valor . '</div>
										</td>';
		$linhas_informacao_tabela .= '</tr>';

	}



	$escutas = get_escutas();
	
	$lista_escutas_str = "";

	// Escutas mais novas primeiro
	foreach ( array_reverse( $escutas ) as $escuta ) {

		if( $escuta["id-acolhido"] !== $id ) continue;
		
		$palavras_chave_str = "";

		foreach( explode( ",", $escuta["palavras-chave"] ) as $palavra_chave ) {

			$palavras_chave_str .= "<span class='tag'>" . $palavra_chave . "</span>";

		}
			

		$data_escuta           = date_create( $escuta["created_at"] );
		
		$diff_to_edit_allow    = 86400 * 3; // 3 days in seconds
		$data_escuta_timestamp = $data_escuta->getTimestamp();

		$data_escuta           = date_format( $data_escuta, "d/m/y H:i" );
			

		$nome_profissional_responsavel = "--";
		$id_profissional_responsavel = -2;
		if( $escuta["profissional"][0] ) {
			
			$id_profissional_responsavel = $escuta["profissional"][0];
			if( get_userdata( $id_profissional_responsavel ) ) {

				$nome_profissional_responsavel = get_userdata( $id_profissional_responsavel )->display_name;

			}

		}


		$current_user_id = wp_get_current_user()->data->ID;

		$allow_to_edit  = ( ( time() - $data_escuta_timestamp < $diff_to_edit_allow ) && 
								$current_user_id === $id_profissional_responsavel );

		$escuta_sigilosa = false;


		$origem_demanda_por_busca_ativa = "";
		if ( $escuta["origem-demanda-por-busca-ativa"] ) {
		
			$origem_demanda_por_busca_ativa = "(" . $escuta["origem-demanda-por-busca-ativa"][0] . ")";
			
		}

		$categoria_escuta_lista = "";

		if ( is_array( $escuta["categoria-escuta"] ) ) {

			$categoria_escuta_lista = join( "; ", $escuta["categoria-escuta"] );

		}


		$sub_categoria_escuta_lista = "";

		if ( is_array( $escuta["sub-categoria-escuta"] ) ) {

			$sub_categoria_escuta_lista = join( ", ", $escuta["sub-categoria-escuta"] );

		}


		if( is_array( $escuta["sigiloso"] ) )
			$escuta_sigilosa = ( $escuta["sigiloso"][0] == "Sim" );
		else
			$escuta_sigilosa = ( $escuta["sigiloso"] == "Sim" );

		$aceita_tcl = false;
		if( is_array( $escuta["aceita-tcl"] ) )
			$aceita_tcl = ( $escuta["aceita-tcl"][0] == "Sim" );
		else
			$aceita_tcl = ( $escuta["aceita-tcl"] == "Sim" );


			

		$lista_escutas_str .= "<div class='escuta-card'>
									<div class='escuta-card-header'>
										<p>" . $escuta["origem-demanda"][0] . " " . $origem_demanda_por_busca_ativa . "</p>
										" .
											( $allow_to_edit || true ? "
											<a href='/editar-escuta?id=" . $escuta["id"] . "' class='escuta-card-header-edit-button' target='_blank'>
												<i class='bi bi-pencil' aria-label='Ícone editar' title='Ícone editar'></i>
											</a>" 
											:
											"" )
										. "
									</div>
									<div class='escuta-card-body'>
										<p>" . ((isset($escuta["tipo-escuta"])) ? $escuta["tipo-escuta"][0] : "") . "</p>
										
										<p>" . $categoria_escuta_lista . "</p>
										
										<p>" . $sub_categoria_escuta_lista . "</p>
										
										<p>" . $escuta["descricao-origem-demanda"] . "</p>

										<p>" . 
											( ( $escuta_sigilosa && $current_user_id !== $id_profissional_responsavel ) 
											? 
											"<div class='escuta-body-block-content'></div>" 
											: 
											$escuta["encaminhamento"] ) 
											. 
										"</p>
										<p>" . 
											( ( $escuta_sigilosa && $current_user_id !== $id_profissional_responsavel ) 
											? 
											"<div class='escuta-body-block-content-large'></div>" 
											: 
											$escuta["anotacoes"] ) 
											.
										"</p>
									</div>
									<div class='escuta-card-footer'>
										<div class='footer-options'>
											<div class='d-flex gap-1'>
												<i class='bi bi-clock' aria-label='Ícone relógio' title='Ícone relógio'></i>
												<span>" . $data_escuta . "</span>
											</div>
											<div class='d-flex gap-1'>
												<i class='bi bi-person' aria-label='Ícone responsável' title='Ícone responsável'></i>
												<span>" . $nome_profissional_responsavel . "</span>
											</div>
											" .
											( 
												( $aceita_tcl ) ? 
												"<div class='d-flex gap-1'>
													<i class='bi bi-hand-thumbs-up' aria-label='Ícone aceita TCL' title='Ícone aceita TCL'></i>
													<span>TCL</span>
												</div>" 
												: 
												"<div class='d-flex gap-1'>
													<i class='bi bi-hand-thumbs-down' aria-label='Ícone rejeita TCL' title='Ícone rejeita TCL'></i>
													<span>TCL</span>
												</div>" 
											) 
											. "
											" .
											( 
												( $escuta_sigilosa ) ? 
												"<div class='d-flex gap-1'>
													<i class='bi bi-eye-slash' aria-label='Ícone escuta sigilosa' title='Ícone escuta sigilosa'></i>
													<span>Sigiloso</span>
												</div>" 
												: 
												"" 
											) 
											. "
										</div>
										<div class='footer-tags'>
											" . $palavras_chave_str . "
										</div>
									</div>
								</div>";
	}

    $data_cadastro = date_create( $acolhido["created_at"] );
    $data_cadastro = date_format( $data_cadastro, "d/m/Y" );

	$lista_escutas_str = ( ( $lista_escutas_str !== "" ) ? $lista_escutas_str : get_hero_error("Nenhuma escuta cadastrada") );

	$caminho_foto = 
		isset( $acolhido["fafar-cf7crud-file-foto"] ) ? 
		$cfdb7_dir_url . '/' . $acolhido["fafar-cf7crud-file-foto"] : 
		get_stylesheet_directory_uri() . "/assets/img/user-icon.svg";

	return "
			    <div class='d-flex flex-column gap-2 mb-4'>
                    <div class='d-flex justify-content-between align-items-baseline'>
                        <h2>" . ( empty( $acolhido["nome-social"] ) ? $acolhido["nome"] : $acolhido["nome-social"] ) . "</h2>

                        <small><em>Cadastrado em " . $data_cadastro . "</em></small>
                    </div>
					
					
					<div class='container-perfil-imagem'>
						<img
							src='" . $caminho_foto . "'
							alt='Foto Acolhido'
							class='w-25 h-25'
						/>
					</div>

					<div class='d-flex justify-content-between align-items-center gap-4 my-4'>
						<a class='fafar-aw-clean-button has-ast-global-color-2-color has-ast-global-color-5-background-color' 
							href='https://escuta.farmacia.ufmg.br/editar-acolhido?id=" . $id . "'>
								<i class='bi bi-pencil' aria-label='Ícone editar' title='Ícone editar'></i>
								<span>Acolhido</span>
						</a>


						<div class='hr-com-div linhas-entre-botoes'></div>


                        <div class='d-flex gap-4'>
                            <a class='fafar-aw-clean-button has-ast-global-color-0-color has-ast-global-color-5-background-color' 
                            href='/nova-escuta?id-acolhido=" . $id . "'>
                                <i class='bi bi-plus-circle' aria-label='Ícone adicionar' title='Ícone adicionar'></i>
                                <span>Escuta</span>
                            </a>

                            <a class='fafar-aw-clean-button has-ast-global-color-2-color has-ast-global-color-5-background-color' 
                                href='https://escuta.farmacia.ufmg.br/agendar-escuta?id=" . $id . "'>
                                    <i class='bi bi-calendar-event' aria-label='Ícone calendário para agendar' title='Ícone calendário'></i>
                                    <span>Agendar</span>
                            </a>
                        </div>
					</div>


					<table class='table border-start-0'>
						<tbody>
							" . $linhas_informacao_tabela . "
						</tbody>
					</table>
					
					<h2 class='fw-bold'>Escutas</h2>
					" . $lista_escutas_str . "
				</div>";

}

/**
 * ---------------------------------------------------------------------------------------------------------------------
**/

function format_date_and_time_to_timestamp( $date, $time, $timezone_hours_decrease = 0, $timezone_hours_increase = 0, $in_ms = false ) {

    $timezone_hours_decrease_diff = 3600 * $timezone_hours_decrease;
    $timezone_hours_increase_diff = 3600 * $timezone_hours_increase;

    $date_time_obj       = date_create( $date . ' ' . $time );
    $date_time_timestamp = $date_time_obj->getTimestamp();

    $date_time_timestamp = $date_time_timestamp - $timezone_hours_decrease_diff;
    $date_time_timestamp = $date_time_timestamp + $timezone_hours_increase_diff;

    return ( $in_ms ) ? ( $date_time_timestamp * 1000 ) : $date_time_timestamp;

}

function gerar_calendario_escutas() {

    $escutas = get_escutas();

    $current_user_id = wp_get_current_user()->data->ID;
	
    $eventos_escutas_json = '';

	foreach ( $escutas as $escuta ) {

        if( $escuta['profissional'][0] !== $current_user_id ) continue;

        if( ! $escuta['data-proxima-escuta'] ) continue;
			
		$proxima_escuta_inicio_timestamp = format_date_and_time_to_timestamp( $escuta['data-proxima-escuta'], $escuta['hora-proxima-escuta'][0], 0, 3, true ) ;
		$proxima_escuta_fim_timestamp    = format_date_and_time_to_timestamp( $escuta['data-proxima-escuta'], $escuta['hora-proxima-escuta'][0], 0, 3, true ) ;

        $acolhido      = get_submission_by_id( $escuta['id-acolhido'] );
        $acolhido_nome = ( ( $acolhido ) ? ( ( $acolhido['nome-social'] ) ? $acolhido['nome-social'] : $acolhido['nome'] ) : 'SEM NOME' );

        $eventos_escutas_json .= '{'; 
        $eventos_escutas_json .= 'title: "' . $acolhido_nome . '",'; 
        $eventos_escutas_json .= 'start: ' . $proxima_escuta_inicio_timestamp . ','; 
        $eventos_escutas_json .= 'end: ' . ( $proxima_escuta_inicio_timestamp + (3600 * 1000) ) . ','; 
        $eventos_escutas_json .= 'cod_usuario: "' . $escuta["id-acolhido"] . '",'; 
        $eventos_escutas_json .= 'id: ' . (int) $escuta["id"] . ',';
        //$eventos_escutas_json .= 'editable: true,'; 
        $eventos_escutas_json .= '}, ';

    }



    wp_enqueue_script( 'full-calendar-lib', 'https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js', false );

    $content = "<div id='calendario-escutas'></div>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                    var calendarEl = document.getElementById('calendario-escutas');

                    var calendar = new FullCalendar.Calendar(calendarEl, {
                        headerToolbar: { center: 'dayGridMonth,timeGridWeek' },
                        initialView: 'timeGridWeek',
                        views: {
                            week: {
                            // name of view
                            titleFormat: {
                                month: '2-digit',
                                day: '2-digit',
                                year: 'numeric',
                            },
                            // other view-specific options here
                            },
                        },
                        hiddenDays: [ ],
                        eventClick: () => {}, 
                        events: [
                            " . $eventos_escutas_json . "
                        ],
                        });
                        calendar.setOption('locale', 'br');
                        calendar.render();
                    });
                </script>";
    
    wp_enqueue_script( 'calendario-escutas', get_stylesheet_directory_uri() . '/assets/js/calendario.js', false );

    echo $content;
}