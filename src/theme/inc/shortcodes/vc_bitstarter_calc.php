<?php

class WPBakeryShortCode_Bitstarter_Calc extends  WPBakeryShortCode
{
    /**
     * @param $atts
     * @param null $content
     * @return string
     */
    public function content($atts, $content = null)
    {
        $header = $rise = $css = '';

        extract(shortcode_atts(array(
            'header'    => 'ESTIMATION',
            'rise'    => '3.5',
            'css'           => ''
        ), $atts));

        $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );



        $output = '';


        $output .= '<div id="bitstarter-calc" class="wpb_content_element bitstarter-calc ' . $css_class . '"><div class="bitstarter-calc__in">';
        $output .= '<h5 class="bitstarter-calc__header">'. wp_kses( $header, bitstarter_allowed_html()) .'</h5>';
        $output .= '<div class="bitstarter-calc__entry">
                        <div class="bitstarter-calc__entry__coin">
                            <div class="form-inline">
                                <input tabindex="1" id="bitstarter-calc-cryptoamount" type="number" min="0" step="any" class="convert__value" placeholder="1.00" aria-label="Home bitstarter amount">
                                <select tabindex="1" id="bitstarter-calc-cryptoname" name="cryptocoin">
                                    <option title="Bitcoin" value="bitstarter">' . esc_html__('BTC','bitstarter') . '</option>
                                    <option title="Ethereum" value="ethereum">' . esc_html__('ETH','bitstarter') . '</option>
                                    <option title="Ripple" value="ripple">' . esc_html__('XRP','bitstarter') . '</option>
                                    <option title="Bitstarter Cash" value="bitstarter-cash">' . esc_html__('BCH','bitstarter') . '</option>
                                    <option title="Cardano" value="cardano">' . esc_html__('ADA','bitstarter') . '</option>
                                    <option title="Litecoin" value="litecoin">' . esc_html__('LTC','bitstarter') . '</option>
                                    <option title="NEM" value="nem">' . esc_html__('XEM','bitstarter') . '</option>
                                    <option title="NEO" value="neo">' . esc_html__('NEO','bitstarter') . '</option>
                                    <option title="IOTA" value="iota">' . esc_html__('MIOTA','bitstarter') . '</option>
                                    <option title="Stellar" value="stellar">' . esc_html__('XLM','bitstarter') . '</option>
                                    <option title="Dash" value="dash">' . esc_html__('DASH','bitstarter') . '</option>
                                    <option title="EOS" value="eos">' . esc_html__('EOS','bitstarter') . '</option>
                                    <option title="Monero" value="monero">' . esc_html__('XMR','bitstarter') . '</option>
                                    <option title="TRON" value="tron">' . esc_html__('TRX','bitstarter') . '</option>
                                    <option title="Bitstarter Gold" value="bitstarter-gold">' . esc_html__('BTG','bitstarter') . '</option>
                                    <option title="Ethereum Classic" value="ethereum-classic">' . esc_html__('ETC','bitstarter') . '</option>
                                    <option title="Qtum" value="qtum">' . esc_html__('QTUM','bitstarter') . '</option>
                                    <option title="ICON" value="icon">' . esc_html__('ICX','bitstarter') . '</option>
                                    <option title="Lisk" value="lisk">' . esc_html__('LSK','bitstarter') . '</option>
                                    <option title="Tether" value="tether">' . esc_html__('USDT','bitstarter') . '</option>
                                    <option title="RaiBlocks" value="raiblocks">' . esc_html__('XRB','bitstarter') . '</option>
                                    <option title="OmiseGO" value="omisego">' . esc_html__('OMG','bitstarter') . '</option>
                                    <option title="Siacoin" value="siacoin">' . esc_html__('SC','bitstarter') . '</option>
                                    <option title="Zcash" value="zcash">' . esc_html__('ZEC','bitstarter') . '</option>
                                    <option title="Ardor" value="ardor">' . esc_html__('ARDR','bitstarter') . '</option>
                                    <option title="Populous" value="populous">' . esc_html__('PPT','bitstarter') . '</option>
                                    <option title="Stratis" value="stratis">' . esc_html__('STRAT','bitstarter') . '</option>
                                    <option title="Binance Coin" value="binance-coin">' . esc_html__('BNB','bitstarter') . '</option>
                                    <option title="VeChain" value="vechain">' . esc_html__('VEN','bitstarter') . '</option>
                                    <option title="Verge" value="verge">' . esc_html__('XVG','bitstarter') . '</option>
                                    <option title="Bytecoin" value="bytecoin-bcn">' . esc_html__('BCN','bitstarter') . '</option>
                                    <option title="Status" value="status">' . esc_html__('SNT','bitstarter') . '</option>
                                    <option title="Waves" value="waves">' . esc_html__('WAVES','bitstarter') . '</option>
                                    <option title="Steem" value="steem">' . esc_html__('STEEM','bitstarter') . '</option>
                                    <option title="KuCoin Shares" value="kucoin-shares">' . esc_html__('KCS','bitstarter') . '</option>
                                    <option title="BitShares" value="bitshares">' . esc_html__('BTS','bitstarter') . '</option>
                                    <option title="Dogecoin" value="dogecoin">' . esc_html__('DOGE','bitstarter') . '</option>
                                    <option title="0x" value="0x">' . esc_html__('ZRX','bitstarter') . '</option>
                                    <option title="Augur" value="augur">' . esc_html__('REP','bitstarter') . '</option>
                                    <option title="SmartCash" value="smartcash">' . esc_html__('SMART','bitstarter') . '</option>
                                    <option title="Dragonchain" value="dragonchain">' . esc_html__('DRGN','bitstarter') . '</option>
                                    <option title="Veritaseum" value="veritaseum">' . esc_html__('VERI','bitstarter') . '</option>
                                    <option title="Electroneum" value="electroneum">' . esc_html__('ETN','bitstarter') . '</option>
                                    <option title="Decred" value="decred">' . esc_html__('DCR','bitstarter') . '</option>
                                    <option title="Komodo" value="komodo">' . esc_html__('KMD','bitstarter') . '</option>
                                    <option title="DigiByte" value="digibyte">' . esc_html__('DGB','bitstarter') . '</option>
                                    <option title="Dentacoin" value="dentacoin">' . esc_html__('DCN','bitstarter') . '</option>
                                    <option title="SALT" value="salt">' . esc_html__('SALT','bitstarter') . '</option>
                                    <option title="Ark" value="ark">' . esc_html__('ARK','bitstarter') . '</option>
                                    <option title="QASH" value="qash">' . esc_html__('QASH','bitstarter') . '</option>
                                    <option title="PIVX" value="pivx">' . esc_html__('PIVX','bitstarter') . '</option>
                                    <option title="Golem" value="golem-network-tokens">' . esc_html__('GNT','bitstarter') . '</option>
                                    <option title="Gas" value="gas">' . esc_html__('GAS','bitstarter') . '</option>
                                    <option title="RChain" value="rchain">' . esc_html__('RHOC','bitstarter') . '</option>
                                    <option title="Hshare" value="hshare">' . esc_html__('HSR','bitstarter') . '</option>
                                    <option title="Ethos" value="ethos">' . esc_html__('ETHOS','bitstarter') . '</option>
                                    <option title="WAX" value="wax">' . esc_html__('WAX','bitstarter') . '</option>
                                    <option title="Walton" value="walton">' . esc_html__('WTC','bitstarter') . '</option>
                                    <option title="Loopring" value="loopring">' . esc_html__('LRC','bitstarter') . '</option>
                                    <option title="Basic Attention Token" value="basic-attention-token">' . esc_html__('BAT','bitstarter') . '</option>
                                    <option title="Byteball Bytes" value="byteball">' . esc_html__('GBYTE','bitstarter') . '</option>
                                    <option title="Dent" value="dent">' . esc_html__('DENT','bitstarter') . '</option>
                                    <option title="DigixDAO" value="digixdao">' . esc_html__('DGD','bitstarter') . '</option>
                                    <option title="ZClassic" value="zclassic">' . esc_html__('ZCL','bitstarter') . '</option>
                                    <option title="Kyber Network" value="kyber-network">' . esc_html__('KNC','bitstarter') . '</option>
                                    <option title="Neblio" value="neblio">' . esc_html__('NEBL','bitstarter') . '</option>
                                    <option title="Factom" value="factom">' . esc_html__('FCT','bitstarter') . '</option>
                                    <option title="Aion" value="aion">' . esc_html__('AION','bitstarter') . '</option>
                                    <option title="Aeternity" value="aeternity">' . esc_html__('AE','bitstarter') . '</option>
                                    <option title="Nexus" value="nexus">' . esc_html__('NXS','bitstarter') . '</option>
                                    <option title="MonaCoin" value="monacoin">' . esc_html__('MONA','bitstarter') . '</option>
                                    <option title="FunFair" value="funfair">' . esc_html__('FUN','bitstarter') . '</option>
                                    <option title="Bytom" value="bytom">' . esc_html__('BTM','bitstarter') . '</option>
                                    <option title="MaidSafeCoin" value="maidsafecoin">' . esc_html__('MAID','bitstarter') . '</option>
                                    <option title="Syscoin" value="syscoin">' . esc_html__('SYS','bitstarter') . '</option>
                                    <option title="aelf" value="aelf">' . esc_html__('ELF','bitstarter') . '</option>
                                    <option title="GXShares" value="gxshares">' . esc_html__('GXS','bitstarter') . '</option>
                                    <option title="Power Ledger" value="power-ledger">' . esc_html__('POWR','bitstarter') . '</option>
                                    <option title="ReddCoin" value="reddcoin">' . esc_html__('RDD','bitstarter') . '</option>
                                    <option title="ZCoin" value="zcoin">' . esc_html__('XZC','bitstarter') . '</option>
                                    <option title="Cryptonex" value="cryptonex">' . esc_html__('CNX','bitstarter') . '</option>
                                    <option title="Nxt" value="nxt">' . esc_html__('NXT','bitstarter') . '</option>
                                    <option title="Kin" value="kin">' . esc_html__('KIN','bitstarter') . '</option>
                                    <option title="Request Network" value="request-network">' . esc_html__('REQ','bitstarter') . '</option>
                                    <option title="GameCredits" value="gamecredits">' . esc_html__('GAME','bitstarter') . '</option>
                                    <option title="Bitcore" value="bitcore">' . esc_html__('BTX','bitstarter') . '</option>
                                    <option title="MediBloc" value="medibloc">' . esc_html__('MED','bitstarter') . '</option>
                                    <option title="Enigma" value="enigma-project">' . esc_html__('ENG','bitstarter') . '</option>
                                    <option title="Bancor" value="bancor">' . esc_html__('BNT','bitstarter') . '</option>
                                    <option title="Substratum" value="substratum">' . esc_html__('SUB','bitstarter') . '</option>
                                    <option title="Nebulas" value="nebulas-token">' . esc_html__('NAS','bitstarter') . '</option>
                                    <option title="Quantstamp" value="quantstamp">' . esc_html__('QSP','bitstarter') . '</option>
                                    <option title="DigitalNote" value="digitalnote">' . esc_html__('XDN','bitstarter') . '</option>
                                    <option title="Emercoin" value="emercoin">' . esc_html__('EMC','bitstarter') . '</option>
                                    <option title="TenX" value="tenx">' . esc_html__('PAY','bitstarter') . '</option>
                                    <option title="Particl" value="particl">' . esc_html__('PART','bitstarter') . '</option>
                                    <option title="Skycoin" value="skycoin">' . esc_html__('SKY','bitstarter') . '</option>
                                    <option title="Experience Points" value="experience-points">' . esc_html__('XP','bitstarter') . '</option>
                                    <option title="BitstarterDark" value="bitstarterdark">' . esc_html__('BTCD','bitstarter') . '</option>
                                    <option title="Iconomi" value="iconomi">' . esc_html__('ICN','bitstarter') . '</option>
                                </select>
                            </div>
                            <p class="bitstarter-calc__error">' . esc_html__( 'Only numbers are allowed', 'bitstarter' ) .'</p>
                            <p>Your investment amount</p>
                        </div>
                        <div class="bitstarter-calc__entry__currency">
                            <div class="form-inline">
                                <input tabindex="1" id="bitstarter-calc-currencyamount" type="number" min="0" step="any" class="conve  rt__value" placeholder="1" aria-label="Home currency amount">
                                <select tabindex="1" id="bitstarter-calc-currencyname" name="currency" >
                                    <option value="USD">' . esc_html__( 'USD', 'bitstarter') . '</option>
                                    <option value="EUR">' . esc_html__( 'EUR', 'bitstarter') . '</option>
                                </select>
                            </div>
                            <p class="bitstarter-calc__error">'. esc_html__( 'Only numbers are allowed', 'bitstarter' ) .'</p>
                            <p>Your investment amount</p>
                        </div>
                    </div>
                    
                    <div class="bitstarter-calc__stats" >
                        <ul>
                            <li>
                                <p class="bitstarter-calc__stats__amount" id="bitstarter-rise" data-rise="' . esc_attr( $rise ) . '">' . esc_html($rise) . '%</p>
                                <p class="bitstarter-calc__stats__decs">' . esc_html__('Current daily interest','bitstarter') . '</p>
                            </li>
                            <li>
                                <div class="bitstarter-calc__stats__row"> 
                                    <p class="bitstarter-calc__stats__symbol" data-bind="symbol.income">$</p>
                                    <p class="bitstarter-calc__stats__amount" data-bind="day.income"></p>
                                </div>
                                <p class="bitstarter-calc__stats__decs">' . esc_html__('Daily net income','bitstarter') . '</p>
                            </li>
                            <li>
                                <div class="bitstarter-calc__stats__row"> 
                                    <p class="bitstarter-calc__stats__symbol" data-bind="symbol.income">$</p>
                                    <p class="bitstarter-calc__stats__amount" data-bind="week.income"></p>
                                </div>
                                <p class="bitstarter-calc__stats__decs">' . esc_html__('Weekly net income','bitstarter') . '</p>
                            </li>
                            <li>
                                <div class="bitstarter-calc__stats__row"> 
                                    <p class="bitstarter-calc__stats__symbol" data-bind="symbol.income">$</p>
                                    <p class="bitstarter-calc__stats__amount" data-bind="month.income"></p>
                                </div>
                                <p class="bitstarter-calc__stats__decs">' . esc_html__('Monthly net income','bitstarter') . '</p>
                            </li>
                        </ul>
                    </div>

                    </div> </div>';
                    

        return $output;
    }
}

$opts = array(
    'name'		=> esc_html__( 'Crypto Calculator', 'bitstarter'),
    'base'		=> 'bitstarter_calc',
    'controls'		=> 'edit_popup_delete',
    'category'		=> esc_html__('Developed for Bitstarter', 'bitstarter'),
    'icon'		=> get_template_directory_uri() . '/assets/img/vc/bitstarter_crypto_calculator.png',
    'params'		=> array(
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Enter header','bitstarter'),
            'param_name' => 'header'
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Enter current daily interest in percentage(ex. 3.5)','bitstarter'),
            'param_name' => 'rise'
        ),
        array(
            'type' => 'css_editor',
            'heading' => esc_html__( 'Css', 'bitstarter' ),
            'param_name' => 'css',
            'group' => esc_html__( 'Design options', 'bitstarter' ),
        ),
    )
);

vc_map($opts);
new WPBakeryShortCode_Bitstarter_Calc($opts);