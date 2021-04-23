#!/bin/bash
#    fetch metaskirando data
#    Copyright (C) Nathanael Schaeffer
#    Copyright (C) Camptocamp Association
#
#    This program is free software: you can redistribute it and/or modify
#    it under the terms of the GNU Affero General Public License as
#    published by the Free Software Foundation, either version 3 of the
#    License, or (at your option) any later version.
#
#    This program is distributed in the hope that it will be useful,
#    but WITHOUT ANY WARRANTY; without even the implied warranty of
#    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#    GNU Affero General Public License for more details.
#
#    You should have received a copy of the GNU Affero General Public License
#    along with this program.  If not, see <http://www.gnu.org/licenses/>.

curl='curl --max-time 300 --silent'
ua='metaskirando bot'
#odir="/var/www/metaskirando.camptocamp.org/private/data"
odir="/home/metaskirwn/www/data"
t=180

mfadrs="http://www.meteofrance.com/mf3-rpc-portlet/rest/relevemontagne/releve/"
mfadresai="/type/imgnivosesai"
mfadre7j="/type/imgnivose7j"
nivadrs="http:\/\/www.meteofrance.com\/integration\/sim-portail"

download () {
  TMP=$(mktemp)
  sleep $((RANDOM % $t))

  if test -z "$3"; then
    $curl -H "User-Agent: $ua" $2 > $TMP
  else
    $curl -H "User-Agent: $ua" -H "Host: $3" $2 > $TMP
  fi

  CHARSET="$(file -bi $TMP|awk -F "=" '{print $2}')"
  if [ "$CHARSET" != utf-8 ]; then
    echo "change encoding from $CHARSET to utf-8"
    iconv -f "$CHARSET" -t utf8 $TMP -o $odir/"$1".web && rm $TMP
  else
    mv $TMP $odir/"$1".web
  fi

  if [ $? == 0 ]; then
    echo "fetched $2"
  else
    echo "failed fetching $2"
    rm -f $TMP
  fi
}

download skitour      "http://www.skitour.fr/topos/dernieres-sorties.php?nbr=100" &
download volo         "https://www.volopress.net/spip.php?rubrique2" &
download c2c          "https://api.camptocamp.org/outings?act=skitouring&pl=fr&limit=200" &
download gulliver.sr  "https://www.gulliver.it/sci-ripido/" &
download gulliver.sa  "https://www.gulliver.it/sci-alpinismo/" &
download bivouak      "https://www.bivouak.net/topos/liste_des_sorties.php?id_sport=1" &
download gipfelbuch   "https://www.gipfelbuch.ch/gipfelbuch/verhaeltnisse" &
download onice        "http://on-ice.it/onice/onice_reports.php?type=1"&

#TMP=$(mktemp)
#for i in BONNE PORTE ECRIN AIGRG BELLE GRPAR ROCHI MEIJE AIGLE CHEVR ALLAN STHIL LEGUA GALIB PARPA MILLE RESTE AGNEL ORCIE MANIC SPOND CANIG PAULA MAUPA PUIGN HOSPI LARDI MAUPA SOUMC AIGTE; do
# $curl "${mfadrs}ZONE_${i}${mfadresai}" | sed "s/^\"/ZONE_${i}_SAI:\"${nivadrs}/" >> $TMP
#  echo "" >> $TMP
#  $curl "${mfadrs}ZONE_${i}${mfadre7j}" | sed "s/^\"/ZONE_${i}:\"${nivadrs}/" >> $TMP
#  echo "" >> $TMP
#done
#mv $TMP "$odir/data/nivo_links.web"
