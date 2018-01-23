<?php

class WPBakeryShortCode_Bitcoin_Calc extends  WPBakeryShortCode
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


        $output .= '<div id="bitcoin-calc" class="wpb_content_element bitcoin-calc ' . $css_class . '"><div class="bitcoin-calc__in">';
        $output .= '<h5 class="bitcoin-calc__header">'. $header .'</h5>';
        $output .= '<div class="bitcoin-calc__entry">
                        <div class="bitcoin-calc__entry__coin">
                            <div class="form-inline">
                                <input tabindex="1" id="bitcoin-calc-cryptoamount" type="number" min="0" step="any" class="convert__value" placeholder="1.00" aria-label="Home bitcoin amount">
                                <select tabindex="1" id="bitcoin-calc-cryptoname" name="cryptocoin">
                                    <option title="Bitcoin" value="bitcoin">' . esc_html__('BTC','bitcoin') . '</option>
                                    <option title="Ethereum" value="ethereum">' . esc_html__('ETH','bitcoin') . '</option>
                                    <option title="Ripple" value="ripple">' . esc_html__('XRP','bitcoin') . '</option>
                                    <option title="Bitcoin Cash" value="bitcoin-cash">' . esc_html__('BCH','bitcoin') . '</option>
                                    <option title="Cardano" value="cardano">' . esc_html__('ADA','bitcoin') . '</option>
                                    <option title="Litecoin" value="litecoin">' . esc_html__('LTC','bitcoin') . '</option>
                                    <option title="NEM" value="nem">' . esc_html__('XEM','bitcoin') . '</option>
                                    <option title="NEO" value="neo">' . esc_html__('NEO','bitcoin') . '</option>
                                    <option title="IOTA" value="iota">' . esc_html__('MIOTA','bitcoin') . '</option>
                                    <option title="Stellar" value="stellar">' . esc_html__('XLM','bitcoin') . '</option>
                                    <option title="Dash" value="dash">' . esc_html__('DASH','bitcoin') . '</option>
                                    <option title="EOS" value="eos">' . esc_html__('EOS','bitcoin') . '</option>
                                    <option title="Monero" value="monero">' . esc_html__('XMR','bitcoin') . '</option>
                                    <option title="TRON" value="tron">' . esc_html__('TRX','bitcoin') . '</option>
                                    <option title="Bitcoin Gold" value="bitcoin-gold">' . esc_html__('BTG','bitcoin') . '</option>
                                    <option title="Ethereum Classic" value="ethereum-classic">' . esc_html__('ETC','bitcoin') . '</option>
                                    <option title="Qtum" value="qtum">' . esc_html__('QTUM','bitcoin') . '</option>
                                    <option title="ICON" value="icon">' . esc_html__('ICX','bitcoin') . '</option>
                                    <option title="Lisk" value="lisk">' . esc_html__('LSK','bitcoin') . '</option>
                                    <option title="Tether" value="tether">' . esc_html__('USDT','bitcoin') . '</option>
                                    <option title="RaiBlocks" value="raiblocks">' . esc_html__('XRB','bitcoin') . '</option>
                                    <option title="OmiseGO" value="omisego">' . esc_html__('OMG','bitcoin') . '</option>
                                    <option title="Siacoin" value="siacoin">' . esc_html__('SC','bitcoin') . '</option>
                                    <option title="Zcash" value="zcash">' . esc_html__('ZEC','bitcoin') . '</option>
                                    <option title="Ardor" value="ardor">' . esc_html__('ARDR','bitcoin') . '</option>
                                    <option title="Populous" value="populous">' . esc_html__('PPT','bitcoin') . '</option>
                                    <option title="Stratis" value="stratis">' . esc_html__('STRAT','bitcoin') . '</option>
                                    <option title="Binance Coin" value="binance-coin">' . esc_html__('BNB','bitcoin') . '</option>
                                    <option title="VeChain" value="vechain">' . esc_html__('VEN','bitcoin') . '</option>
                                    <option title="Verge" value="verge">' . esc_html__('XVG','bitcoin') . '</option>
                                    <option title="Bytecoin" value="bytecoin-bcn">' . esc_html__('BCN','bitcoin') . '</option>
                                    <option title="Status" value="status">' . esc_html__('SNT','bitcoin') . '</option>
                                    <option title="Waves" value="waves">' . esc_html__('WAVES','bitcoin') . '</option>
                                    <option title="Steem" value="steem">' . esc_html__('STEEM','bitcoin') . '</option>
                                    <option title="KuCoin Shares" value="kucoin-shares">' . esc_html__('KCS','bitcoin') . '</option>
                                    <option title="BitShares" value="bitshares">' . esc_html__('BTS','bitcoin') . '</option>
                                    <option title="Dogecoin" value="dogecoin">' . esc_html__('DOGE','bitcoin') . '</option>
                                    <option title="0x" value="0x">' . esc_html__('ZRX','bitcoin') . '</option>
                                    <option title="Augur" value="augur">' . esc_html__('REP','bitcoin') . '</option>
                                    <option title="SmartCash" value="smartcash">' . esc_html__('SMART','bitcoin') . '</option>
                                    <option title="Dragonchain" value="dragonchain">' . esc_html__('DRGN','bitcoin') . '</option>
                                    <option title="Veritaseum" value="veritaseum">' . esc_html__('VERI','bitcoin') . '</option>
                                    <option title="Electroneum" value="electroneum">' . esc_html__('ETN','bitcoin') . '</option>
                                    <option title="Decred" value="decred">' . esc_html__('DCR','bitcoin') . '</option>
                                    <option title="Komodo" value="komodo">' . esc_html__('KMD','bitcoin') . '</option>
                                    <option title="DigiByte" value="digibyte">' . esc_html__('DGB','bitcoin') . '</option>
                                    <option title="Dentacoin" value="dentacoin">' . esc_html__('DCN','bitcoin') . '</option>
                                    <option title="SALT" value="salt">' . esc_html__('SALT','bitcoin') . '</option>
                                    <option title="Ark" value="ark">' . esc_html__('ARK','bitcoin') . '</option>
                                    <option title="QASH" value="qash">' . esc_html__('QASH','bitcoin') . '</option>
                                    <option title="PIVX" value="pivx">' . esc_html__('PIVX','bitcoin') . '</option>
                                    <option title="Golem" value="golem-network-tokens">' . esc_html__('GNT','bitcoin') . '</option>
                                    <option title="Gas" value="gas">' . esc_html__('GAS','bitcoin') . '</option>
                                    <option title="RChain" value="rchain">' . esc_html__('RHOC','bitcoin') . '</option>
                                    <option title="Hshare" value="hshare">' . esc_html__('HSR','bitcoin') . '</option>
                                    <option title="Ethos" value="ethos">' . esc_html__('ETHOS','bitcoin') . '</option>
                                    <option title="WAX" value="wax">' . esc_html__('WAX','bitcoin') . '</option>
                                    <option title="Walton" value="walton">' . esc_html__('WTC','bitcoin') . '</option>
                                    <option title="Loopring" value="loopring">' . esc_html__('LRC','bitcoin') . '</option>
                                    <option title="Basic Attention Token" value="basic-attention-token">' . esc_html__('BAT','bitcoin') . '</option>
                                    <option title="Byteball Bytes" value="byteball">' . esc_html__('GBYTE','bitcoin') . '</option>
                                    <option title="Dent" value="dent">' . esc_html__('DENT','bitcoin') . '</option>
                                    <option title="DigixDAO" value="digixdao">' . esc_html__('DGD','bitcoin') . '</option>
                                    <option title="ZClassic" value="zclassic">' . esc_html__('ZCL','bitcoin') . '</option>
                                    <option title="Kyber Network" value="kyber-network">' . esc_html__('KNC','bitcoin') . '</option>
                                    <option title="Neblio" value="neblio">' . esc_html__('NEBL','bitcoin') . '</option>
                                    <option title="Factom" value="factom">' . esc_html__('FCT','bitcoin') . '</option>
                                    <option title="Aion" value="aion">' . esc_html__('AION','bitcoin') . '</option>
                                    <option title="Aeternity" value="aeternity">' . esc_html__('AE','bitcoin') . '</option>
                                    <option title="Nexus" value="nexus">' . esc_html__('NXS','bitcoin') . '</option>
                                    <option title="MonaCoin" value="monacoin">' . esc_html__('MONA','bitcoin') . '</option>
                                    <option title="FunFair" value="funfair">' . esc_html__('FUN','bitcoin') . '</option>
                                    <option title="Bytom" value="bytom">' . esc_html__('BTM','bitcoin') . '</option>
                                    <option title="MaidSafeCoin" value="maidsafecoin">' . esc_html__('MAID','bitcoin') . '</option>
                                    <option title="Syscoin" value="syscoin">' . esc_html__('SYS','bitcoin') . '</option>
                                    <option title="aelf" value="aelf">' . esc_html__('ELF','bitcoin') . '</option>
                                    <option title="GXShares" value="gxshares">' . esc_html__('GXS','bitcoin') . '</option>
                                    <option title="Power Ledger" value="power-ledger">' . esc_html__('POWR','bitcoin') . '</option>
                                    <option title="ReddCoin" value="reddcoin">' . esc_html__('RDD','bitcoin') . '</option>
                                    <option title="ZCoin" value="zcoin">' . esc_html__('XZC','bitcoin') . '</option>
                                    <option title="Cryptonex" value="cryptonex">' . esc_html__('CNX','bitcoin') . '</option>
                                    <option title="Nxt" value="nxt">' . esc_html__('NXT','bitcoin') . '</option>
                                    <option title="Kin" value="kin">' . esc_html__('KIN','bitcoin') . '</option>
                                    <option title="Request Network" value="request-network">' . esc_html__('REQ','bitcoin') . '</option>
                                    <option title="GameCredits" value="gamecredits">' . esc_html__('GAME','bitcoin') . '</option>
                                    <option title="Bitcore" value="bitcore">' . esc_html__('BTX','bitcoin') . '</option>
                                    <option title="MediBloc" value="medibloc">' . esc_html__('MED','bitcoin') . '</option>
                                    <option title="Enigma" value="enigma-project">' . esc_html__('ENG','bitcoin') . '</option>
                                    <option title="Bancor" value="bancor">' . esc_html__('BNT','bitcoin') . '</option>
                                    <option title="Substratum" value="substratum">' . esc_html__('SUB','bitcoin') . '</option>
                                    <option title="Nebulas" value="nebulas-token">' . esc_html__('NAS','bitcoin') . '</option>
                                    <option title="Quantstamp" value="quantstamp">' . esc_html__('QSP','bitcoin') . '</option>
                                    <option title="DigitalNote" value="digitalnote">' . esc_html__('XDN','bitcoin') . '</option>
                                    <option title="Emercoin" value="emercoin">' . esc_html__('EMC','bitcoin') . '</option>
                                    <option title="TenX" value="tenx">' . esc_html__('PAY','bitcoin') . '</option>
                                    <option title="Particl" value="particl">' . esc_html__('PART','bitcoin') . '</option>
                                    <option title="Skycoin" value="skycoin">' . esc_html__('SKY','bitcoin') . '</option>
                                    <option title="Experience Points" value="experience-points">' . esc_html__('XP','bitcoin') . '</option>
                                    <option title="BitcoinDark" value="bitcoindark">' . esc_html__('BTCD','bitcoin') . '</option>
                                    <option title="Iconomi" value="iconomi">' . esc_html__('ICN','bitcoin') . '</option>
                                </select>
                            </div>
                            <p class="bitcoin-calc__error">' . esc_html__( 'Only numbers are allowed', 'bitcoin' ) .'</p>
                            <p>Your investment amount</p>
                        </div>
                        <div class="bitcoin-calc__entry__currency">
                            <div class="form-inline">
                                <input tabindex="1" id="bitcoin-calc-currencyamount" type="number" min="0" step="any" class="conve  rt__value" placeholder="1" aria-label="Home currency amount">
                                <select tabindex="1" id="bitcoin-calc-currencyname" name="currency" >
                                    <option value="USD">' . esc_html__( 'USD', 'bitcoin') . '</option>
                                    <option value="EUR">' . esc_html__( 'EUR', 'bitcoin') . '</option>
                                </select>
                            </div>
                            <p class="bitcoin-calc__error">'. esc_html__( 'Only numbers are allowed', 'bitcoin' ) .'</p>
                            <p>Your investment amount</p>
                        </div>
                    </div>
                    
                    <div class="bitcoin-calc__stats" >
                        <ul>
                            <li>
                                <p class="bitcoin-calc__stats__amount" id="bitcoin-rise" data-rise="' . esc_attr( $rise ) . '">' . esc_html($rise) . '%</p>
                                <p class="bitcoin-calc__stats__decs">' . esc_html__('Current daily interest','bitcoin') . '</p>
                            </li>
                            <li>
                                <div class="bitcoin-calc__stats__row"> 
                                    <p class="bitcoin-calc__stats__symbol" data-bind="symbol.income">$</p>
                                    <p class="bitcoin-calc__stats__amount" data-bind="day.income"></p>
                                </div>
                                <p class="bitcoin-calc__stats__decs">' . esc_html__('Daily net income','bitcoin') . '</p>
                            </li>
                            <li>
                                <div class="bitcoin-calc__stats__row"> 
                                    <p class="bitcoin-calc__stats__symbol" data-bind="symbol.income">$</p>
                                    <p class="bitcoin-calc__stats__amount" data-bind="week.income"></p>
                                </div>
                                <p class="bitcoin-calc__stats__decs">' . esc_html__('Weekly net income','bitcoin') . '</p>
                            </li>
                            <li>
                                <div class="bitcoin-calc__stats__row"> 
                                    <p class="bitcoin-calc__stats__symbol" data-bind="symbol.income">$</p>
                                    <p class="bitcoin-calc__stats__amount" data-bind="month.income"></p>
                                </div>
                                <p class="bitcoin-calc__stats__decs">' . esc_html__('Monthly net income','bitcoin') . '</p>
                            </li>
                        </ul>
                    </div>

                    </div> </div>';
                    

        return $output;
    }
}

$opts = array(
    'name'		=> esc_html__( 'Crypto Calculator', 'bitcoin'),
    'base'		=> 'bitcoin_calc',
    'controls'		=> 'edit_popup_delete',
    'category'		=> esc_html__('Developed for Bitcoin', 'bitcoin'),
    'icon'		=> get_template_directory_uri() . '/assets/img/vc/bitcoin_blog.png',
    'params'		=> array(
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Enter header','bitcoin'),
            'param_name' => 'header'
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Enter current daily interest in percentage(ex. 3.5)','bitcoin'),
            'param_name' => 'rise'
        ),
        array(
            'type' => 'css_editor',
            'heading' => esc_html__( 'Css', 'bitcoin' ),
            'param_name' => 'css',
            'group' => esc_html__( 'Design options', 'bitcoin' ),
        ),
    )
);

vc_map($opts);
new WPBakeryShortCode_Bitcoin_Calc($opts);