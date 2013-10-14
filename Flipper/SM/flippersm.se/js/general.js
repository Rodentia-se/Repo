var scripts = new Array();
var debugMode = 1;
// var baseHref = (document.getElementById('baseHref')) ? document.getElementById('baseHref').value : 'https://www.flippersm.se';
var baseHref = '';
var pageMode = 'register';
var choice = true;

// The classes variable contains meta-information about the classes.
var classes = {
  continent: {
    name: 'continent',
    geo: true,
    plural: 'continents',
    headers: ['name', 'latitude', 'longitude'], // Headers normally used in tables and lists
    fields: {
      name: { label: 'Namn', type: 'text', mandatory: true, special: false, bundle: false, default: ''},
      latitude: { label: 'Latitud', type: 'text', mandatory: false, special: false, bundle: false, default: ''},
      longitude: { label: 'Longitud', type: 'text', mandatory: false, special: false, bundle: false, default: ''}
    },
    complete: false
  },
  country: {
    name: 'country',
    geo: true,
    plural: 'countries',
    headers: ['name', 'continent', 'latitude', 'longitude'], // Headers normally used in tables and lists
    fields: {
      name: { label: 'Namn', type: 'text', mandatory: true, special: false, bundle: false, default: ''},
      continent: { label: 'Kontinent', type: 'text', mandatory: true, special: false, bundle: false},
      latitude: { label: 'Latitud', type: 'text', mandatory: false, special: false, bundle: false, default: ''},
      longitude: { label: 'Longitud', type: 'text', mandatory: false, special: false, bundle: false, default: ''}
    },
    complete: false
  },
  region: {
    name: 'region',
    geo: true,
    plural: 'regions',
    headers: ['name', 'country', 'continent', 'latitude', 'longitude'], // Headers normally used in tables and lists
    fields: {
      name: { label: 'Namn', type: 'text', mandatory: true, special: false, bundle: false, default: ''},
      continent: { label: 'Kontinent', type: 'text', mandatory: true, special: false, bundle: 1},
      country: { label: 'Land', type: 'text', mandatory: true, special: false, bundle: 1},
      latitude: { label: 'Latitud', type: 'text', mandatory: false, special: false, bundle: false, default: ''},
      longitude: { label: 'Longtitud', type: 'text', mandatory: false, special: false, bundle: false, default: ''}
    },
    complete: false
  },
  city: {
    name: 'city',
    geo: true,
    plural: 'cities',
    headers: ['name', 'region', 'country', 'continent', 'latitude', 'longitude'], // Headers normally used in tables and lists
    fields: {
      name: { label: 'Namn', type: 'text', mandatory: true, special: false, bundle: false, default: ''},
      continent: { label: 'Kontinent', type: 'text', mandatory: true, special: false, bundle: 1},
      country: { label: 'Land', type: 'text', mandatory: true, special: false, bundle: 1},
      region: { label: 'Landskap', type: 'text', mandatory: true, special: false, bundle: 1},
      latitude: { label: 'Latitud', type: 'text', mandatory: false, special: false, bundle: false, default: ''},
      longitude: { label: 'Longitud', type: 'text', mandatory: false, special: false, bundle: false, default: ''}
    },
    complete: false
  },
  game: {
    name: 'game',
    geo: false,
    plural: 'games',
    headers: ['name', 'type', 'acronym', 'manufacturer', 'year', 'ipdb', 'rules'], // Headers normally used in tables and lists
    fields: {
      name: { label: 'Namn', type: 'text', mandatory: false, special: false, bundle: false, default: ''},
      type: { label: 'Typ', type: 'select', mandatory: false, special: false, bundle: false, default: ''},
      acronym: { label: 'Kortnamn', type: 'text', mandatory: false, special: false, bundle: false, default: ''},
      manufacturer: { label: 'Tillverkare', type: 'select', mandatory: true, special: false, bundle: 1},
      year: { label: 'År', type: 'text', mandatory: false, special: false, bundle: false, default: ''},
      ipdb: { label: 'IPDB', type: 'text', mandatory: false, special: false, bundle: false, default: ''},
      rules: { label: 'Regler', type: 'text', mandatory: false, special: false, bundle: false, default: ''}
    },
    complete: false
  },
  manufacturer: {
    name: 'manufacturer',
    geo: false,
    plural: 'manufacturers',
    headers: ['name', 'link'], // Headers normally used in tables and lists
    fields: {
      name: { label: 'Namn', type: 'text', mandatory: false, special: false, bundle: false, default: ''},
      link: { label: 'Länk', type: 'text', mandatory: false, special: false, bundle: false, default: ''}
    },
    complete: false
  },
  team: {
    name: 'team',
    geo: false,
    plural: 'teams',
    headers: ['name', 'initials'], // Headers normally used in tables and lists
    fields: {
      id: { label: 'ID', type: 'hidden', mandatory: false, special: false, bundle: false, default: 0},
      name: { label: 'Namn', type: 'text', mandatory: true, special: false, bundle: false, default: ''},
      initials: { label: 'TAG', type: 'text', mandatory: false, special: false, bundle: false, default: ''},
      dateRegistered: { label: 'Anmäld', type: 'hidden', mandatory: false, special: false, bundle: false, default: ''},
      tournamentDivision_id: { label: 'Division', type: 'hidden', mandatory: false, special: false, bundle: false, default: ''},
      registerPerson_id: { label: 'Anmäld av', type: 'hidden', mandatory: false, special: false, bundle: false, default: ''},
      contactPlayer_id: { label: 'Kapten', type: 'radio', mandatory: false, special: false, bundle: false, default: ''}
    },
    complete: false
  },
  player: {
    name: 'player',
    geo: false,
    plural: 'players',
    headers: ['initials', 'name', 'city', 'region', 'ifpaRank', 'classics', 'dateRegistered', 'paid'], // Headers normally used in tables and lists
    fields: {
      id: { label: 'ID', type: 'hidden', mandatory: false, special: false, bundle: false, default: 0},
      name: { label: 'Namn', type: 'hidden', mandatory: false, special: false, bundle: false, default: ''},
      ifpa_id: { label: 'IFPA ID', type: 'hidden', mandatory: false, special: false, bundle: false, default: 0},
      ifpaRank: { label: 'IFPA', type: 'hidden', mandatory: false, special: false, bundle: false, default: 0},
      class: { label: 'Klass', type: 'hidden', mandatory: false, special: false, bundle: false, default: 'player'},
      isPlayer: { label: 'isPlayer', type: 'hidden', mandatory: false, special: false, bundle: false, default: true},
      isPerson: { label: 'isPerson', type: 'hidden', mandatory: false, special: false, bundle: false, default: true},
      isIfpa: { label: 'isIfpa', type: 'hidden', mandatory: false, special: false, bundle: false, default: true},
      firstName: { label: 'Förnamn', type: 'text', mandatory: true, special: false, bundle: false, default: ''},
      lastName: { label: 'Efternamn', type: 'text', mandatory: true, special: false, bundle: false, default: ''},
      initials: { label: 'TAG', type: 'text', mandatory: false, special: false, bundle: false, default: ''},
      username: { label: 'Användarnamn', type: 'text', mandatory: true, special: false, bundle: false, default: ''},
      password: { label: 'Lösenord', type: 'password', mandatory: true, special: false, bundle: false, default: ''},
      passwordRequired: { label: 'Password', type: 'hidden', mandatory: false, special: false, bundle: false, default: true},
      gender: { label: 'Kön', type: 'select', mandatory: false, special: false, bundle: false},
      streetAddress: { label: 'Gatuadress', type: 'text', mandatory: false, special: false, bundle: false, default: ''},
      zipCode: { label: 'Postnummer', type: 'text', mandatory: false, special: false, bundle: false, default: ''},
      city: { label: 'Hemort', type: 'select', mandatory: false, special: 'add', bundle: false},
      region: { label: 'Landskap', type: 'select', mandatory: false, special: false, bundle: false},
      country: { label: 'Land', type: 'select', mandatory: true, special: false, bundle: false},
      continent: { label: 'Kontinent', type: 'select', mandatory: false, special: false, bundle: false},
      telephoneNumber: { label: 'Telefon', type: 'text', mandatory: false, special: false, bundle: false, default: ''},
      mobileNumber: { label: 'Mobil', type: 'text', mandatory: true, special: false, bundle: false, default: ''},
      mailAddress: { label: 'Mailadress', type: 'text', mandatory: true, special: false, bundle: false, default: ''},
      main: { label: 'Main', type: 'checkbox', mandatory: false, special: false, bundle: 1, default: true},
      classics: { label: 'Classics', type: 'checkbox', mandatory: false, special: false, bundle: 1, default: false},
      paid: { label: 'Betalat', type: 'checkbox', mandatory: false, special: false, bundle: 1, default: false},
      volunteer: { label: 'Volontär', type: 'checkbox', mandatory: false, special: false, bundle: false, default: true},
      birthDate: { label: 'Född', type: 'text', mandatory: false, special: 'date', bundle: false, default: ''},
      dateRegistered: { label: 'Anmäld', type: 'hidden', mandatory: false, special: false, bundle: false, default: new Date().toISOString().substring(0,10)}
    },
    complete: false
  },
  gender: {
    name: 'gender',
    geo: false,
    plural: 'genders',
    headers: ['name'],
    mandatory: ['name'],
    fields: {
      name: { label: 'Namn', type: 'text', mandatory: true, special: false, bundle: false}
    },
    complete: true
  }
}

var continents = []; // Contains all continents
var countries = []; // Contains all countries
var regions = []; // Contains all regions
var cities = []; // Contains all cities
var players = []; // Contains all players
var genders = []; // Contains all genders
var games = []; // Contains all games
var manufacturers = []; // Contains all manufacturers
var teams = []; // Contains all teams

var currencies = {
  SEK: {
    name: 'kronor',
    shortName: 'SEK',
    symbol: ' kr',
    symbolPlace: 1,
    rate: 1
  }
}

/*
if ($.url().attr('file') == 'adminTools.php') {
  $.fn.dataTableExt.afnSortData['dom-link'] = function(oSettings, iColumn) {
	  return $.map( oSettings.oApi._fnGetTrNodes(oSettings), function (tr, i) {
		  return $('td:eq('+iColumn+') a', tr).text().replace(/^$/, '20000').replace(/^0$/, '20000');
	  });
  }
}
*/

// Classes definitions for continents, countries, regions, cities, players and genders.
// Implemented as JS functions with protoypes.
function CONTINENT(data) {
  if (continents.indexOf(data) == -1) {
    this.id = data.id;
    this.name = data.name;
    this.altName = data.altName;
    this.class = 'continent';
    this.comment = data.comment;
    this.latitude = data.latitude;
    this.longitude = data.longitude;
    this.countries = []; // Contains all countries on the continent
    this.regions = []; // Contains all regions on the continent
    this.cities = []; // Contains all cities on the continent
    this.players = []; // Contains all players from the continent
    this.link = addLink(this);
    if (this.id !=0) {
      continents.push(this); // Add to the global continents array
    }
  } else {
    this.remove();
  }
}

CONTINENT.prototype = {
  remove:  function() {
    continents.splice(continents.indexOf(this),1); // Remove from the global continents array.
  },
  // Add children objects:
  addCountry: function(country) { // Add a country to the array with all countries on the continent
    if (this.countries.indexOf(country) == -1) {
      this.countries.push(country);
      country.continent_id = this.id;
    }
  },
  addRegion: function(region) { // Add a region to the array with all regions on the continent
    if (this.regions.indexOf(region) == -1) {
      this.regions.push(region);
      region.continent_id = this.id;
    }
  },
  addCity: function(city) { // Add a city to the array with all cities on the continent
    if (this.cities.indexOf(city) == -1) {
      this.cities.push(city);
      city.continent_id = this.id;
    }
  },
  addPlayers: function(player) { // Add a player to the array with all players from the continent
    if (this.players.indexOf(player) == -1) {
      this.players.push(player);
      player.continent_id = this.id;
    }
  },
  addParents: function() { // Continents don't have parents
  },
  addLinks: function() { // Used to generate links for linkable fields.
    this.links = {
      name: this.link,
      latitude: false,
      longitude: false
    }
  }
}

function COUNTRY(data) {
  if (countries.indexOf(data) == -1) {
    this.id = data.id;
    this.name = data.name;
    this.altName = data.altName;
    this.class = 'country';
    this.comment = data.comment;
    this.latitude = data.latitude;
    this.longitude = data.longitude;
    this.continent_id = data.continent_id;
    this.continent = null;
    this.capitalCity_id = data.capitalCity_id;
    this.regions = []; // Contains all regions in the country
    this.cities = []; // Contains all cities in the country
    this.players = []; // Contains all players from the country
    this.link = addLink(this);
    if (this.id !=0) {
      countries.push(this); // Add to the global countries array
    }
  } else {
    this.remove();
  }
}

COUNTRY.prototype = {
  remove: function() {
    countries.splice(countries.indexOf(this),1); // Remove from the global countries array.
    var obj = this.getContinent();
    obj.countries.splice(obj.countries.indexOf(this),1); // Take the dead child away from the parent
  },
  // Add children objects:
  addRegion: function(region) { // Add a region to the array with all region in the country
    if (this.regions.indexOf(region) == -1) {
      this.regions.push(region);
      region.country_id = this.id;
    }
  },
  addCity: function(city) { // Add a city to the array with all cities in the country
    if (this.cities.indexOf(city) == -1) {
      this.cities.push(city);
      city.country_id = this.id;
    }
  },
  addPlayer: function(player) { // Add a player to the array with all players from the country
    if (this.players.indexOf(player) == -1) {
      this.players.push(player);
      player.country_id = this.id;
    }
  },
  getContinent: function() {
    return continent(this.continent_id);
  },
  addParents: function() {
    addParent(this, 'continent');
  },
  addLinks: function() { // Used to generate links for linkable fields.
    this.links = {
      name: this.link,
      continent: addLink(continent(this.continent_id)),
      latitude: false,
      longitude: false
    }
  }
}

function REGION(data) {
  if (regions.indexOf(data) == -1) {
    this.id = data.id;
    this.name = data.name;
    this.altName = data.altName;
    this.class = 'region';
    this.comment = data.comment;
    this.latitude = data.latitude;
    this.longitude = data.longitude;
    this.continent_id = data.continent_id;
    this.country_id = data.country_id;
    this.continent = null;
    this.country = null;
    this.capitalCity_id = data.capitalCity_id;
    this.cities = []; // Contains all cities in the region
    this.players = []; // Contains all players from the region
    this.link = addLink(this);
    if (this.id !=0) {
      regions.push(this); // Add to the global regions array
    }
  } else {
    this.remove();
  }
}

REGION.prototype = {
  remove: function() {
    regions.splice(regions.indexOf(this),1); // Remove from the global regions array.
    var obj = this.getContinent();
    obj.regions.splice(obj.regions.indexOf(this),1); // Take the dead grandchild away from the grandparent
    var obj = this.getCountry();
    obj.regions.splice(obj.regions.indexOf(this),1); // Take the dead child away from the parent
  },
  // Add children objects:
  addCity: function(city) { // Add a city to the array with all cities in the region
    if (this.cities.indexOf(city) == -1) {
      this.cities.push(city);
      city.region_id = this.id;
    }
  },
  addPlayer: function(player) { // Add a player to the array with all players from the region
    if (this.players.indexOf(player) == -1) {
      this.players.push(player);
      player.region_id = this.id;
    }
  },
  getContinent: function() {
    return continent(this.continent_id);
  },
  getCountry: function() {
    return country(this.country_id);
  },
  addParents: function() {
    addParent(this, 'continent');
    addParent(this, 'country');
  },
  addLinks: function() { // Used to generate links for linkable fields.
    this.links = {
      name: this.link,
      country: addLink(country(this.country_id)),
      continent: addLink(continent(this.continent_id)),
      latitude: false,
      longitude: false
    }
  }
}

function CITY(data) {
  if (cities.indexOf(data) == -1) {
    this.id = data.id;
    this.name = data.name;
    this.altName = data.altName;
    this.class = 'city';
    this.comment = data.comment;
    this.latitude = data.latitude;
    this.longitude = data.longitude;
    this.continent_id = data.continent_id;
    this.country_id = data.country_id;
    this.region_id = data.region_id;
    this.continent = null;
    this.country = null;
    this.region = null;
    this.players = []; // Contains all players from the city
    this.link = addLink(this);
    if (this.id !=0) {
      cities.push(this); // Add to the global cities array
    }
  } else {
    this.remove();
  }
}

CITY.prototype = {
  remove: function() {
    cities.splice(cities.indexOf(this),1); // Remove from the global cities array.
    var obj = this.getContinent();
    obj.cities.splice(obj.cities.indexOf(this),1); // Take the dead great grandchild away from the great grandparent
    var obj = this.getCountry();
    obj.cities.splice(obj.cities.indexOf(this),1); // Take the dead grandchild away from the grandparent
    var obj = this.getRegion();
    obj.cities.splice(obj.cities.indexOf(this),1); // Take the dead child away from the parent
  },
  // Add children objects:
  addPlayer: function(player) { // Add a player to the array with all players from the city
    if (this.players.indexOf(player) == -1) {
      this.players.push(player);
      player.city_id = this.id;
    }
  },
  getContinent: function() {
    return continent(this.continent_id);
  },
  getCountry: function() {
    return country(this.country_id);
  },
  getRegion: function() {
    return region(this.region_id);
  },
  addParents: function() {
    addParent(this, 'continent');
    addParent(this, 'country');
    addParent(this, 'region');
  },
  addLinks: function() { // Used to generate links for linkable fields.
    this.links = {
      name: this.link,
      region: addLink(region(this.region_id)),
      country: addLink(country(this.country_id)),
      continent: addLink(continent(this.continent_id)),
      latitude: false,
      longitude: false
    }
  }
}

function PLAYER(data) {
  if (players.indexOf(data) == -1) {
    this.id = data.id;
    this.name = (data.name) ? data.name : data.firstName + ' ' + data.lastName;
    this.class = 'player';
    this.comment = data.comment;
    this.isIfpa = data.isIfpa;
    this.ifpaRank = data.ifpaRank;
    this.isPerson = data.isPerson;
    this.isPlayer = data.isPlayer;
    this.ifpa_id = data.ifpa_id;
    this.type = data.type;
    this.player_ids = data.player_ids;
    this.firstName = data.firstName;
    this.lastName = data.lastName;
    this.initials = data.initials;
    this.gender = data.gender;
    this.gender_id = data.gender_id;
    this.streetAddress = data.streetAddress;
    this.zipCode = data.zipCode;
    this.city = data.city;
    this.city_id = data.city_id;
    this.region = data.region;
    this.region_id = data.region_id;
    this.country = data.country;
    this.country_id = data.country_id;
    this.continent_id = data.continent_id;
    this.continent = data.continent;
    this.telephoneNumber = data.telephoneNumber;
    this.mobileNumber = (data.mobileNumber && data.mobileNumber != '') ? data.mobileNumber : data.telephoneNumber;
    this.mailAddress = data.mailAddress;
    this.dateRegistered = data.dateRegistered;
    this.birthDate = data.birthDate;
    this.classics = data.classics;
    this.main = data.main;
    this.paid = (data.paid > 0) ? 1 : 0;
    this.volunteer = data.volunteer;
    this.username = data.username;
    this.password = data.password;
    this.passwordRequired = data.passwordRequired;
    this.link = addLink(this);
    if (this.id !=0) {
      players.push(this); // Add to the global players array
    }
  } else {
    this.remove();
  }
}

PLAYER.prototype = {
  remove: function() {
    players.splice(players.indexOf(this),1); // Remove from the global players array.
    var obj = this.getContinent();
    obj.players.splice(obj.players.indexOf(this),1); // Take the dead great great grandchild away from the great great  grandparent
    var obj = this.getCountry();
    obj.players.splice(obj.players.indexOf(this),1); // Take the dead great grandchild away from the great grandparent
    var obj = this.getRegion();
    obj.players.splice(obj.players.indexOf(this),1); // Take the dead grandchild away from the grandparent
    var obj = this.getCity();
    obj.players.splice(obj.players.indexOf(this),1); // Take the dead child away from the parent
  },
  getContinent: function() {
    return continent(this.continent_id);
  },
  getCountry: function() {
    return country(this.country_id);
  },
  getRegion: function() {
    return region(this.region_id);
  },
  getCity: function() {
    return city(this.city_id);
  },
  addParents: function() {
    addParent(this, 'continent');
    addParent(this, 'country');
    addParent(this, 'region');
    addParent(this, 'city');
  },
  addLinks: function() { // Used to generate links for linkable fields.
    this.links = {
      name: this.link,
      initials: false,
      ifpaRank: addIfpaLink(this.ifpa_id, this.ifpaRank),
      city: addLink(city(this.city_id)),
      region: addLink(region(this.region_id)),
      country: addLink(country(this.country_id)),
      continent: addLink(continent(this.continent_id))
    }
  }
}

function GENDER(data) {
  if (genders.indexOf(data) == -1) {
    this.id = data.id;
    this.name = (data.name) ? data.name : data.firstName + ' ' + data.lastName;
    this.class = 'gender';
    this.comment = data.comment;
    this.link = addLink(this);
    if (this.id !=0) {
      genders.push(this); // Add to the global genders array
    }
  } else {
    this.remove();
  }
}

GENDER.prototype = {
  remove: function() {
    genders.splice(genders.indexOf(this),1); // Remove from the global genders array.
  },
  addLinks: function() { // Used to generate links for linkable fields.
    this.links = {
      name: this.link
    }
  }
}

function GAME(data) {
  if (games.indexOf(data) == -1) {
    this.class = 'game';
    this.id = data.id;
    this.name = data.name;
    this.type = data.type;
    this.acronym = data.acronym;
    this.manufacturer = data.manufacturer;
    this.manufacturer_id = data.manufacturer_id;
    this.year = data.year;
    this.ipdb_id = data.ipdb_id;
    this.rules = data.rules;
    this.comment = data.comment;
    this.link = addLink(this);
    if (this.id !=0) {
      games.push(this); // Add to the global games array
    }
  } else {
    this.remove();
  }
}

GAME.prototype = {
  remove: function() {
    games.splice(gamse.indexOf(this),1); // Remove from the global games array.
    var obj = this.getManufacturer();
    obj.games.splice(obj.games.indexOf(this),1); // Take the dead child away from the parent
  },
  getManufacturer: function() {
    return manufacturer(this.manufacturer_id);
  },
  addParents: function() {
    addParent(this, 'manufacturer');
  },
  addLinks: function() { // Used to generate links for linkable fields.
    this.links = {
      name: this.link,
      type: addTypeLink(this.type),
      acronym: false,
      year: false,
      manufacturer: addLink(manufacturer(this.manufacturer_id)),
      ipdb: addIpdbLink(this.ipdb_id),
      rules: ((this.rules.length > 5) ? addRulesLink(this.rules) : '')
    }
  }
}

function MANUFACTURER(data) {
  if (manufacturers.indexOf(data) == -1) {
    this.id = data.id;
    this.name = data.name;
    this.link = data.link;
    this.class = 'manufacturer';
    this.comment = data.comment;
    this.games = []; // Contains all games from the manufacturer
    this.link = addLink(this);
    if (this.id !=0) {
      manufacturers.push(this); // Add to the global manufacturers array
    }
  } else {
    this.remove();
  }
}

MANUFACTURER.prototype = {
  remove:  function() {
    manufacturers.splice(manufacturers.indexOf(this),1); // Remove from the global manufacturers array.
  },
  // Add children objects:
  addGame: function(game) { // Add a game to the array with all games from the manufacturer
    if (this.games.indexOf(game) == -1) {
      this.games.push(game);
      game.manufacturer_id = this.id;
    }
  },
  addParents: function() { // Manufacturers don't have parents
  },
  addLinks: function() { // Used to generate links for linkable fields.
    this.links = {
      name: this.link,
      link: false
    }
  }
}

function TEAM(data) {
  if (teams.indexOf(data) == -1) {
    this.id = data.id;
    this.name = data.name;
    this.initials = data.initials;
    this.class = 'team';
    this.contactPlayer_id = data.contactPlayer_id;
    this.registerPerson_id = data.registerPerson_id;
    this.tournamentDivision_id = data.tournamentDivision_id;
    this.dateRegistered = data.dateRegistered;
    this.comment = data.comment;
    this.players = []; // Contains all players from the team
    this.link = addLink(this);
    if (this.id !=0) {
      teams.push(this); // Add to the global teams array
    }
  } else {
    this.remove();
  }
}

TEAM.prototype = {
  remove:  function() {
    teams.splice(teams.indexOf(this),1); // Remove from the global teams array.
  },
  // Add children objects:
  addPlayer: function(player) { // Add a player to the array with all players on the team
    if (this.players.indexOf(player) == -1) {
      this.players.push(player);
      player.team_id = this.id;
    }
  },
  addParents: function() { // Teams don't have parents
  },
  addLinks: function() { // Used to generate links for linkable fields.
    this.links = {
      name: this.link,
      link: false
    }
  }
}

// Function called from the search button on the registration page. "id" is not necessarily an IFPA ID - it might as well be a phone number, email address, TAG or (parts of) a person's name. What to search for is determined by the regexps in the getPlayerByIfpaId function in functions/general.php. The results table is normally shown in the ifpaRegResults div  (dstId).
function ifpaReg(id, dstId) {
  document.getElementById('newButton').disabled = false;
  if (!classes['player'].fields.username) {
    classes['player'].fields.username = { label: 'Username', type: 'text', mandatory: true, special: false, bundle: false, default: ''};
    classes['player'].fields.password = { label: 'Password', type: 'password', mandatory: true, special: false, bundle: false, default: ''};
  }
  var tbl = document.getElementById(dstId + 'Table');
  if ($('#playerEditDiv')) {
    $('#playerEditDiv').hide();
  }
  $('#ifpaRegResultsTableDiv').hide();
  showLoading(classes['player']);
  try {
    $('#' + tbl.id).dataTable.fnClearTable(); // Clear the datatable layout (and the table) if the datatable object exists...
  } catch(err) {
    tbl.innerHTML = ''; // ...otherwise empty the whole table. (Nulling the table with datatable object attached will make datatable freak out.)
  }
 $.getJSON(baseHref + '/ajax/ifpaReg.php',{ifpaId: $('#' + id).val()}) // Returns a JSON with all hits
  .done(function(data) {
    var type = 'player';
    var objs = [];
    players.length = 0; // Let's delete all the players...
    hideLoading(classes['player']);
    if (data && data.length > 0) {
      for (var obj in data) {
        new window[type.toUpperCase()](data[obj]); // ...and create new player objects from the JSON.
      }
      $('#' + dstId + 'TableDiv').show();
      $('#ifpaRegResultsTableDiv').show();
      printPlayers(players, dstId);
    } else {
      $('#noHits').show();
    }
  })
  .fail(function(jqHXR,status,error) {
    debugOut('Fail: S: ' + status + ' E: ' + error); // Oh, no! Fail!
    debugOut(jqHXR.responseText);
  });
}

// Print a table with all loaded players. 
// "meBtns" true/false = add the "This is me!" button at the end of each row.
// "sels" true/false = make all suitable fields into selects containing all objects of that class, with the current one pre-selected (to let a player change his/her city, or similar).
function printPlayers(objs, dstId, meBtns, sels) {
  meBtns = (typeof meBtns === 'undefined') ? true : meBtns; // Default is true
  var tbl = document.getElementById(dstId + 'Table');
  try {
    $('#' + tbl.id).dataTable.fnClearTable(); // Clear the datatable layout (and the table) if the datatable object exists...
  } catch(err) {
    tbl.innerHTML = ''; // ...otherwise empty the whole table. (Nulling the table with datatable object attached will make datatable freak out.)
  }
  addThead(tbl, classes['player'], meBtns); // Add table headers (don't forget the header for the meBtn!)
  var tbody = addTbody(tbl);
  addRows(tbody, classes['player'], false, meBtns, sels, objs); // Add the rows, and include info about meBtn and sels.
  $('#' + dstId).show(); // Allright, table done, let's show it
  if ($.url().attr('file') == 'adminTools.php') {
    $('#' + tbl.id).dataTable({
      'bProcessing': true,
      'bDestroy': true,
      'bJQueryUI': true,
	  	'sPaginationType': 'full_numbers',
      'iDisplayLength': 200,
		  'aoColumns': [
	      {'sSortDataType': 'dom-link' },
	  		null,
	      {'sSortDataType': 'dom-link' },
	      {'sSortDataType': 'dom-link' },
	      {'sSortDataType': 'dom-link' },
	      {'sSortDataType': 'dom-link' },
	      {'sSortDataType': 'dom-link' },
        null
  		],
      'aLengthMenu': [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']]
    }); // Rebuild the datatable
    $('#' + tbl.id).css('width', ''); // This is needed, or the table is butt ugly!
    $('#newData').css('width', ''); // This is needed, or the table is butt ugly!
  }
}

// This is me!
function thisIsMe(btnId) {
  var obj = player(btnId.id.replace('meBtn_', '')); // Let's find out who "me" is and make him a player
/*
  if ((!obj.passwordRequired || obj.passwordRequired == 0) && obj.id != 0) { // This user has already registered! He can log in to do his stuff.
    return false;
    window.location.href = baseHref + '/editplayer/?obj=player&id=' + btnId.id.replace('meBtn_', '');
  } else {
  */
    printPlayerAsList(obj,'ifpaRegResults'); // Show the details form to the player, with info pre-filled in.
    window.scrollTo(0,680);
//  }
}

function editPlayer(id) {
  pageMode = 'edit';
  showLoading(classes['player']);
  $.getJSON(baseHref + '/ajax/player.php?id=' + id) // Returns a JSON with all hits
  .done(function(data) {
    var type = 'player';
    var objs = [];
    players.length = 0; // Let's delete all the players...
    if (data.length > 0) {
      for (var obj in data) {
        new window[type.toUpperCase()](data[obj]); // ...and create new player objects from the JSON.
      }
      var obj = player(id);
      hideLoading(classes['player']);
      if (document.getElementById('user') && obj.username.toLowerCase() == document.getElementById('user').value.toLowerCase()) {
        printPlayerAsList(player(id),'ifpaRegResults');
      } else {
        alert('You are not allowed to edit that player!');
      }
    }
  })
  .fail(function(jqHXR,status,error) {
    debugOut('Fail: S: ' + status + ' E: ' + error); // Oh, no! Fail!
    debugOut(jqHXR.responseText);
  });
}

// I'm a new guy!
function newGuy(dstId) {
  $('#noHits').hide();
  if (!classes.player.fields.username) {
    classes.player.fields.username = { label: 'Username', type: 'text', mandatory: true, special: false, bundle: false, default: ''};
    classes.player.fields.password = { label: 'Password', type: 'password', mandatory: true, special: false, bundle: false, default: ''};
  }
  printPlayerAsList(player(), dstId); // Create a dummy player with no info, and show the (non-existing) details form fo the player.
}

function checkIfpaBtn(el, event) {
  $('#noHits').hide();
  setTimeout(function() { 
    if (/^[0-9]{1,}$|.{3,}/.test(el.value)) {
      document.getElementById('ifpaButton').disabled = (complete('geo')) ? false : true;
      if (!document.getElementById('ifpaButton').disabled) {
        if (event != 'noclick') {
          enterClick('ifpaButton', event);
        }
      }
    } else {
      document.getElementById('ifpaButton').disabled = true;
    }
  }, 100);
}

function resetPassword(el) {
  var admin = (document.getElementById('adminReset') && document.getElementById('adminReset').value == 'true') ? 1 : 0 ;
  if (admin) {
    var playerId = el.id.split('_')[0];
    var pw = prompt('You are about to change password for the user ID ' + playerId + '! Type the new password and press OK, or press cancel to bail out.', '!chAng3m3');
  } else {
    var playerId = document.getElementById('personId').value;
    var pw = document.getElementById('password').value;
  }
  $.post(baseHref + '/ajax/resetPass.php', {playerId: playerId, pw: pw, nonce: document.getElementById('resetNonce').value, admin: admin})
  .done(function(data) {
    fade(document.getElementById(((admin) ? el.id + 'Span' : 'passwordSpan')), data.reason, data.success);
    if (admin) {
      $('.' + el.id.split('_')[1]).attr('disabled', true);
    }
    if (data.success) {
      if (!admin) {
        setTimeout( function() {
          window.location.href = baseHref + '/?s=loggain';
        }, 500);
      }
    }
    return false;
  })
  .fail(function(jqHXR,status,error) {
    debugOut('Fail: S: ' + status + ' E: ' + error); // Oh, no! Fail!
    debugOut(jqHXR.responseText);
  });
  return false;
}

function resetPass(el) {
  if (el.id == 'username') {
    document.getElementById('email').value = '';
  } else {
    document.getElementById('username').value = '';    
  }
  $.post(baseHref + '/ajax/checkReset.php', {f: el.id, v: el.value})
  .done(function(data) {
    if (data.success) {
      document.getElementById('submit').disabled = false;
    } else {
      document.getElementById('submit').disabled = true;
    }
    fade(document.getElementById(el.id + 'Span'), data.reason, data.success);     
  })
  .fail(function(jqHXR,status,error) {
    debugOut('Fail: S: ' + status + ' E: ' + error); // Oh, no! Fail!
    debugOut(jqHXR.responseText);
  });
}

function checkResetPassword(el) {
  $.post(baseHref + '/ajax/checkField.php', {f: 'password', v: el.value}) // Let's check the fields
  .done(function(data) {
    document.getElementById('submit').disabled = !data.valid;
    fade(document.getElementById(el.id + 'Span'), data.reason, data.valid);     
  })
  .fail(function(jqHXR,status,error) {
    debugOut('Fail: S: ' + status + ' E: ' + error); // Oh, no! Fail!
    debugOut(jqHXR.responseText);
  });
}

// This prints out the details form. Now done with meta arrays.
function printPlayerAsList(obj,dstId) {
  if ((!obj.passwordRequired || obj.passwordRequired == 0) && obj.id != 0) {
    delete classes[obj.class].fields.username;
    delete classes[obj.class].fields.password;
    delete obj.password;
  }
  $('#' + dstId + 'TableDiv').hide(); // Hide the table div with players - either we've found the player or this is a new guy.
  $('#' + dstId).show(); // And show the details form
  if ($('#playerEditDiv')) {
    $('#playerEditDiv').remove(); // Kill the player table
  }
  var div = document.createElement('div'); // Details form table. Table based layout design ftw!
  div.id = 'playerEditDiv';
  var h3 = document.createElement('h3');
  h3.appendChild(document.createTextNode('Bekräfta, ändra och lägg till din information. Gula fält är obligatoriska.'));
  div.appendChild(h3);
  for (var prop in classes[obj.class].fields) { // Loop through all object properties (player names and such)
    if (prop != 'paid') {
      addFieldDiv(div, obj, prop);
      if (prop == 'birthDate') {
        var birthDateP = document.createElement('p');
        birthDateP.className = 'comment';
        birthDateP.id = 'birthDateP';
        birthDateP.innerHTML = 'OBS: Födelsedatum är obligatoriskt för U18.';
        div.appendChild(birthDateP);
      }
    }
  }
  if (!document.getElementById('nonPlayerLogin') && (!document.getElementById('loggedIn') || document.getElementById('loggedIn').value != 'true')) {
    var recapP = document.createElement('p');
    recapP.id = 'recaptcha'; // Let's add a Google Recaptcha to keep robots away. They can't play pinball anyway!
    div.appendChild(recapP);
  }
  var submitDiv = document.createElement('div');
  var lbl = document.createElement('label');
  lbl.id = 'submitTextLabel';
  var txt = document.createTextNode('Skicka'); // We have it all! Let's play!
  lbl.appendChild(txt);
  submitDiv.appendChild(lbl);
  var btn = document.createElement('input');
  btn.type = 'button';
  btn.id = 'submit';
  btn.value = 'Skicka!';
  btn.onclick = function() { checkForm(this); return false; };
  submitDiv.appendChild(btn);
  var span = document.createElement('span'); // Let's put the result from the field check in here
  span.id = 'submitSpan';
  span.className += ' errorSpan';
  submitDiv.appendChild(span);
  div.appendChild(submitDiv);
  if (!document.getElementById('nonPlayerLogin') && (!document.getElementById('loggedIn') || document.getElementById('loggedIn').value != 'true')) {
    setTimeout(function() { // Recaptcha is faster than its shadow (or at least faster than creating a div and giving it an ID in the dom), so we need to delay it for 100ms, or it won't find its div. Strange but true.
      Recaptcha.create('6Lc3d-gSAAAAAMN5x6kTerCfM9IGytZTcN2IIEyw', 'recaptcha', {
        theme: 'blackglass'
      })
    }, 100);
  }
  document.getElementById(dstId).appendChild(div); // Let's show it to the user
  // Hard coded shit! Remove when chance given!
  selectOption(document.getElementById('countrySelect'), 188);
  selectOption(document.getElementById('continentSelect'), 1);
  document.getElementById('countrySelect').disable = true;
  document.getElementById('continentSelect').disable = true;
}

function addFieldRow(tbody, obj, prop) {
  var type = classes[obj.class].fields[prop].type;
  if (type != 'hidden') { // No label or td needed for hidden stuff
    var tr = tbody.insertRow(-1);
    tr.id = prop + 'Tr';
    var lblTd = tr.insertCell(-1); // Label TD
    lblTd.id = prop + 'LabelTd';
    var lbl = document.createElement('label');
    lbl.id = prop + 'Label';
    var txt = document.createTextNode(classes[obj.class].fields[prop].label); // Field name.
    lbl.appendChild(txt);
    var td = tr.insertCell(-1);
    td.id = prop + 'Td';
  } else {
    $('#' + prop + 'Hidden').remove(); // If this is not the first time this player found him/her self, there will be old hidden fields laying around. Let's remove those.
  }
  var input = document.createElement((type == 'select') ? 'select' : 'input');
  input.name = prop; // Name is actual property name, as defined in the database.
  input.id = prop + ucfirst(type); // ...while ID will also tell what type of input it is.
  if (type == 'select') {
    popSel(input);
    selectOption(input, obj[prop + '_id']);
    input.onchange = (prop != 'gender') ? function () { geoSelected(this); } : ''; // When the user has selected one geo-object, then let's adapt all the other ones. If choosing Sweden, only Swedish regions and cities will be shown in the other dropdowns. And if a city in Uppland is chosen - Uppland, Sweden and Europe are automatically selected.
  } else {
    input.type = type;
    if (type == 'checkbox') {
      input.checked = (obj[prop] == 1) ? true : false;
    } else {
      input.value = (obj[prop]) ? obj[prop] : classes[obj.class].fields[prop].default;
    }
    if (prop == 'main') {
      input.disabled = true;
      input.checked = true;
    }
    if ((prop == 'classics' || prop == 'volunteer') && !choice) {
      input.disabled = true;
    }
//    input[(type == 'checkbox') ? 'checked' : 'value'] = (obj[prop]) ? obj[prop] : classes[obj.class].fields[prop].default; // Add existing value, or the default value
    input.onchange = function() { checkField(this); }; // Let's check that the field is correct. Only done when leaving the field (changed), and not at every keypress.
    if (classes[obj.class].fields[prop].special == 'date') {
      setTimeout(function() { $('#' + input.id).datepicker({ dateFormat: 'yy-mm-dd', changeYear: true, yearRange: '-100:-0', changeMonth: true }); }, 100); // Fancy date picker added!
    }
  }
  if (type != 'hidden') { // No label, td or span needed for hidden stuff
    lbl.for = input.id;
    td.appendChild(input);
    lblTd.appendChild(lbl);
    var span = document.createElement('span'); // Let's put the result from the field check in here
    span.id = prop + 'Span';
    span.className += ' errorSpan';
    if (classes[obj.class].fields[prop].mandatory) {
      input.className += ' mandatory'; // Mandatory fields will become yellow
      span.appendChild(document.createTextNode('*')); // Let's be very clear!
    }
    td.appendChild(span);
    if (input.id == 'passwordPassword') {
      var span2 = document.createElement('span'); // Let's put the result from the field check in here
      span2.id = prop + 'Span2';
      span2.className += ' errorSpan';
      td.appendChild(span2);
    }
    if (classes[obj.class].fields[prop].special == 'add') { // Users have a possibility to add cities and regions not already in the database. So let's add some elements for that.
      var img = document.createElement('img'); // A plus sign after the select, to click on to add stuff.
      img.id = prop + 'Add';
      img.src = baseHref + '/images/add_icon.gif';
      img.className += ' icon';
      img.onclick = function () { geoAdd(this, true); }; // They want to add something (true)!
      img.alt = 'Klicka för att lägga till en ny ' + classes[obj.class].fields[prop].label.toLowerCase();
      img.title = img.alt;
      td.appendChild(img);
      var selTxt = document.createElement('input'); // So let's replace the select with a text input
      selTxt.type = 'text';
      selTxt.name = selTxt.id; // We can't use actual property name here, since that is used by the dropdown. If this field has a value, the dropdown value is ignored.
      selTxt.id = prop + 'AddText'; // ...so both ID and name will also tell what type of input it is.
      selTxt.className += ' invisible'; // Display none until the plus sign is clicked
      td.appendChild(selTxt);
      var addSpan = document.createElement('span'); // Let's put the result from the field check in here
      addSpan.id = prop + 'AddSpan';
      addSpan.className += ' errorSpan';
      td.appendChild(addSpan);
      var cImg = document.createElement('img'); // What if the user regrets his/her choice, and wants to select an existing item anyway? No problem - just click the X icon! If they do, we will remove the value from the selTxt text input, and use the value from the dropdown.
      cImg.id = prop + 'AddCancel';
      cImg.src = baseHref + '/images/cancel.png';
      cImg.className += ' icon invisible'; // Display none until the plus sign is clicked
      cImg.alt = 'Click to cancel and get back to the dropdown';
      cImg.title = cImg.alt;
      cImg.onclick = function () { geoAdd(this, false ); }; // The don't want to add something (false)!
      td.appendChild(cImg);
    }
    return document.createTextNode('');
  }
  return input;
}

function addFieldDiv(div, obj, prop) {
  var type = classes[obj.class].fields[prop].type;
  if (type != 'hidden') { // No label or div needed for hidden stuff
    var propDiv = document.createElement('div');
    propDiv.id = prop + 'Div';
    div.appendChild(propDiv);
    var lbl = document.createElement('label');
    lbl.id = prop + 'Label';
    var txt = document.createTextNode(classes[obj.class].fields[prop].label); // Field name.
    lbl.appendChild(txt);
    propDiv.appendChild(lbl);
  } else {
    $('#' + prop + 'Hidden').remove(); // If this is not the first time this player found him/her self, there will be old hidden fields laying around. Let's remove those.
  }
  var input = document.createElement((type == 'select') ? 'select' : 'input');
  input.name = prop; // Name is actual property name, as defined in the database.
  input.id = prop + ucfirst(type); // ...while ID will also tell what type of input it is.
  if (type == 'select') {
    popSel(input);
    selectOption(input, obj[prop + '_id']);
    input.onchange = (prop != 'gender') ? function () { geoSelected(this); } : ''; // When the user has selected one geo-object, then let's adapt all the other ones. If choosing Sweden, only Swedish regions and cities will be shown in the other dropdowns. And if a city in Uppland is chosen - Uppland, Sweden and Europe are automatically selected.
  } else {
    input.type = type;
    if (type == 'checkbox') {
      input.checked = (obj[prop] == 1) ? true : false;
    } else {
      input.value = (obj[prop]) ? obj[prop] : classes[obj.class].fields[prop].default;
    }
    if (prop == 'main') {
      input.disabled = true;
      input.checked = true;
    }
    if ((prop == 'classics' || prop == 'volunteer') && !choice) {
      input.disabled = true;
    }
//    input[(type == 'checkbox') ? 'checked' : 'value'] = (obj[prop]) ? obj[prop] : classes[obj.class].fields[prop].default; // Add existing value, or the default value
    input.onchange = function() { checkField(this); }; // Let's check that the field is correct. Only done when leaving the field (changed), and not at every keypress.
    if (classes[obj.class].fields[prop].special == 'date') {
      setTimeout(function() { $('#' + input.id).datepicker({ dateFormat: 'yy-mm-dd', changeYear: true, yearRange: '-100:-0', changeMonth: true }); }, 100); // Fancy date picker added!
    }
  }
  if (type == 'hidden') { // No label, td or span needed for hidden stuff
    div.appendChild(input);
  } else {
    propDiv.appendChild(input);
    lbl.for = input.id;
    var span = document.createElement('span'); // Let's put the result from the field check in here
    span.id = prop + 'Span';
    span.className += ' errorSpan';
    if (classes[obj.class].fields[prop].mandatory) {
      input.className += ' mandatory'; // Mandatory fields will become yellow
      span.appendChild(document.createTextNode('*')); // Let's be very clear!
    }
    propDiv.appendChild(span);
    if (input.id == 'passwordPassword') {
      var span2 = document.createElement('span'); // Let's put the result from the field check in here
      span2.id = prop + 'Span2';
      span2.className += ' errorSpan';
      propDiv.appendChild(span2);
    }
    if (classes[obj.class].fields[prop].special == 'add') { // Users have a possibility to add cities and regions not already in the database. So let's add some elements for that.
      var img = document.createElement('img'); // A plus sign after the select, to click on to add stuff.
      img.id = prop + 'Add';
      img.src = baseHref + '/images/add_icon.gif';
      img.className += ' icon';
      img.onclick = function () { geoAdd(this, true); }; // They want to add something (true)!
      img.alt = 'Klicka för att lägga till en ny ' + classes[obj.class].fields[prop].label.toLowerCase();
      img.title = img.alt;
      propDiv.appendChild(img);
      var selTxt = document.createElement('input'); // So let's replace the select with a text input
      selTxt.type = 'text';
      selTxt.name = selTxt.id; // We can't use actual property name here, since that is used by the dropdown. If this field has a value, the dropdown value is ignored.
      selTxt.id = prop + 'AddText'; // ...so both ID and name will also tell what type of input it is.
      selTxt.className += ' invisible'; // Display none until the plus sign is clicked
      propDiv.appendChild(selTxt);
      var addSpan = document.createElement('span'); // Let's put the result from the field check in here
      addSpan.id = prop + 'AddSpan';
      addSpan.className += ' errorSpan invisible';
      propDiv.appendChild(addSpan);
      var cImg = document.createElement('img'); // What if the user regrets his/her choice, and wants to select an existing item anyway? No problem - just click the X icon! If they do, we will remove the value from the selTxt text input, and use the value from the dropdown.
      cImg.id = prop + 'AddCancel';
      cImg.src = baseHref + '/images/cancel.png';
      cImg.className += ' icon invisible'; // Display none until the plus sign is clicked
      cImg.alt = 'Click to cancel and get back to the dropdown';
      cImg.title = cImg.alt;
      cImg.onclick = function () { geoAdd(this, false ); }; // The don't want to add something (false)!
      propDiv.appendChild(cImg);
    }
    return document.createTextNode('');
  }
  return input;
}

function submit(obj) {
  if (document.getElementById('nonPlayerLogin') || (document.getElementById('loggedIn') && document.getElementById('loggedIn').value == 'true')) {
    checkFields(obj);
  } else {
    $.post(baseHref + '/ajax/recaptcha.php', {resp: Recaptcha.get_response(), chall: Recaptcha.get_challenge()}) // Let's ajax-check the recaptcha
    .done(function(data) { // Allright, recaptcha response received
      Recaptcha.destroy(); // Let's destroy the recaptcha (either we are done and fine, or we need to create a new one anyway)
      if (data == 'Valid') { // Allright, recaptcha was fine!
        checkFields(obj);
      } else {
//        alert('Recaptcha was invalid - try again! ' + data); // Oh, no! Our user can't read!
        alert('Recaptcha was invalid - try again!'); // Oh, no! Our user can't read!
        Recaptcha.create('6Lc3d-gSAAAAAMN5x6kTerCfM9IGytZTcN2IIEyw', 'recaptcha', { //... so let's recreate the recaptcha, and start all over again.
          theme: 'blackglass',
          callback: Recaptcha.focus_response_field
        });
      }
    })
    .fail(function(jqHXR,status,error) {
      debugOut('Fail: S: ' + status + ' E: ' + error); // Oh, no! Fail!
      debugOut(jqHXR.responseText);
    });
  }
}

function teamPhoto() {
  fade(document.getElementById('submitImgSpan'), 'Updaterar databasen...', true);
  $.post(baseHref + '/ajax/teamPhoto.php', {newPhoto: document.getElementById('newPhoto').value}) // Send to server
  .done(function(data) {
    fade(document.getElementById('submitImgSpan'), data.reason, data.success);
    if (data.success) {
      document.getElementById('submitImgSpan').disabled = true;
    }
  })
  .fail(function(jqHXR,status,error) {
    debugOut('Fail: S: ' + status + ' E: ' + error); // Oh, no! Fail!
    debugOut(jqHXR.responseText);
  });
}

function submitChecked(obj) {
  var props = [];
  for (var prop in classes[obj.class].fields) {
    props.push(prop);
    if (classes[obj.class].fields[prop].type == 'select') {
      props.push(prop + '_id'); // Keep the ID fields!
    }
  }
  var jsonObj = JSON.stringify(obj, props); // I stringify the object...
  var newObj = JSON.parse(jsonObj); // ...and then objectify it again. Why? Shouldn't be necessary? I should be able to just use the object straight on? I don't remeber why I did this!
  if (document.getElementById('submitSpan')) {
    fade(document.getElementById('submitSpan'), 'Updaterar databasen...', true);
  }
  $.post(baseHref + ((pageMode == 'register') ? '/ajax/register.php' : ((pageMode == 'edit') ? '/ajax/playerEdit.php?newPhoto=' + document.getElementById('newPhoto').value : ((pageMode == 'team') ? '/ajax/regTeam.php' : 'wrongPageMode'))), newObj) // Send to server
  .done(function(data) {
    if (pageMode == 'team') {
      if (data.success && data.reason.match(/^[0-9]+$/)) {
        fade(document.getElementById('submitSpan'), 'Laget är uppdaterat!', true);
        document.getElementById('idHidden').value = data.reason;
        for (var num = 2; num > 0; num--) {
          var sel = document.getElementById('teamPlayer' + num + 'Select');
          if (sel.value == newObj.registerPerson_id) {
            sel.disabled = true;
            document.getElementById('contactPlayer_id' + num).disabled = false;
            document.getElementById('contactPlayer_id' + num).value = newObj.registerPerson_id;
            $('#teamIncomplete' + num + 'Span').hide();
            var found = true;
          }
          if (sel.value == 0) {
            var emptyNum = num;
            var emptyCaptain = document.getElementById('contactPlayer_id' + num);
            var emptyIncomplete = $('#teamIncomplete' + num + 'Span');
            $('#teamIncomplete' + num + 'Span').show();
          }
        }
        if (!found && emptyNum) {
          var emptySel = document.getElementById('teamPlayer' + emptyNum + 'Select');
          var emptyCaptain = document.getElementById('contactPlayer_id' + emptyNum);
          selectOption(emptySel, newObj.registerPerson_id);
          emptySel.disabled = true;
          emptyCaptain.disabled = false;
          emptyCaptain.value = newObj.registerPerson_id;
          emptyIncomplete.hide();
        }
        showMemberSelects();
      } else {
        var reason = (data.success) ? 'Nånting gick fel - laget uppdaterades inte!' : data.reason ;
        fade(document.getElementById('submitSpan'), reason, false);
      }
    } else { 
      if (document.getElementById('nonPlayerLogin') || (document.getElementById('loggedIn') || document.getElementById('loggedIn').value == 'true')) {
        window.location.href = baseHref + '/?s=object&obj=player&id=self';
      } else {
        $.post(baseHref + '/ajax/login.php', {u: document.getElementById('usernameText').value, p: document.getElementById('passwordPassword').value}) // Let's login
        .done(function(data) {
          window.location.href = baseHref + '/?s=object&obj=player&id=self';
        })
        .fail(function(jqHXR,status,error) {
          debugOut('Fail: S: ' + status + ' E: ' + error); // Oh, no! Fail!
          debugOut(jqHXR.responseText);
        });
      }
    }
  })
  .fail(function(jqHXR,status,error) {
    debugOut('Fail: S: ' + status + ' E: ' + error); // Oh, no! Fail!
    debugOut(jqHXR.responseText);
  });
}

function checkFields(obj) {
  var propArray = [];
  for (var prop in classes[obj.class].fields) {
    if (!(obj.class == 'player' && (prop == 'name' || prop == 'paid'))) {
      propArray.push('{"f":"' + prop + '","v":"' + obj[prop] +'","id":"' + obj.id +'"}');
    }
  }
  $.post(baseHref + '/ajax/checkField.php', {data: propArray}) // Let's check the fields
  .done(function(data) {
    if (pageMode == 'team') {
      document.getElementById('submit').disabled = !data.valid;
    } else {
      if (data.valid) {
        submitChecked(obj);
      } else {
        if (!document.getElementById('nonPlayerLogin') && (!document.getElementById('loggedIn') || document.getElementById('loggedIn').value != 'true')) {
          Recaptcha.create('6Lc3d-gSAAAAAMN5x6kTerCfM9IGytZTcN2IIEyw', 'recaptcha', { // Didn't validate! So let's recreate the recaptcha, and start all over again.
            theme: 'blackglass',
            callback: Recaptcha.focus_response_field
          });
        }
        alert('Something is wrong with ' + data.field + ': ' + data.reason);
      }
    }
  })
  .fail(function(jqHXR,status,error) {
    debugOut('Fail: S: ' + status + ' E: ' + error); // Oh, no! Fail!
    debugOut(jqHXR.responseText);
  });
}

function checkField(el) {
  setTimeout(function() {
    $.post(baseHref + '/ajax/checkField.php', {f: el.name, v: el.value, id: document.getElementById('idHidden').value}) // Let's check the field
    .done(function(data) {
      if (document.getElementById(el.name + 'Span')) {
        var elName = (document.getElementById(el.name + 'Span2')) ? el.name + 'Span2' : el.name + 'Span';
        if (data.valid) {
          if (document.getElementById('action') && document.getElementById('action').value != 'regTeam') {
            var type = 'team';
            pageMode = 'team';
          } else {
            var type = 'player';
          }
          var txt = (el.name.match(/^newPassword[0-9]*/) || classes[type].fields[el.name].mandatory) ? '*' : '';
          if (document.getElementById(el.name + 'Span2')) {
            txt = ' ';
          }
        } else {
          var txt = data.reason;
        }
        $('#' + elName).toggleClass('error', !data.valid); // Add or remove error 
        document.getElementById(elName).innerHTML = '';
        document.getElementById(elName).appendChild(document.createTextNode(txt));
      } 
      if (document.getElementById('action') && document.getElementById('action').value == 'regTeam') {
        var obj = team();
        pageMode = 'team';
        for (var prop in classes[obj.class].fields) {
          var meta = classes[obj.class].fields[prop];
          if (meta.type == 'radio') {
            obj[prop] = $('input[name=' + prop + ucfirst(meta.type) + ']:checked').val();;
          } else {
            obj[prop] = document.getElementById(prop + ucfirst(meta.type)).value;
          }
        }
        checkFields(obj);
      } else {
        if ((document.getElementById('password') || document.getElementById('passwordText')) && (!document.getElementById('passwordRequiredHidden') || (document.getElementById('passwordRequiredHidden').value != '1' && document.getElementById('passwordRequiredHidden').value != 'true'))) {
          checkUsernameFields();
        } else {
          document.getElementById('submit').disabled = !data.valid; // OK or not OK to submit
        }
      }
    })
    .fail(function(jqHXR,status,error) {
      debugOut('Fail: S: ' + status + ' E: ' + error); // Oh, no! Fail!
      debugOut(jqHXR.responseText);
    });
  }, 100);
}

function regTeam() {
  var obj = team(); // ...and create a new object from it. Very annoying.
  for (var prop in classes[obj.class].fields) {
    var meta = classes[obj.class].fields[prop];
    if (meta.type == 'radio') {
      if ($('input:radio[name="' + prop + '"]:checked').length > 0) {
        obj[prop] = $('input:radio[name="' + prop + '"]:checked').val();
      }
    } else {
      obj[prop] = document.getElementById(prop + ucfirst(meta.type)).value;
    }
  }
  if (!obj.contactPlayer_id || !obj.contactPlayer_id.match(/^[0-9]+$/) || obj.contactPlayer_id == '0') {
    obj.contactPlayer_id = obj.registerPerson_id;
  }
  submitChecked(obj);
}

function showMemberSelects() {
  $('.regTeam').hide();
  $('.editTeam').show();
}

function hideMemberSelects() {
  $('.regTeam').show();
  $('.editTeam').hide();
}

function setCaptain() {
  $('input[name=contactPlayer_id]').each(function(index, radio) {
    if (radio.checked == true) {
      var urlParam = {playerId: radio.value};
      fade(document.getElementById('teamPlayer' + radio.id.replace('contactPlayer_id', '') + 'Span'), 'Updaterar databasen...', true);
      $.post(baseHref + '/ajax/captain.php', urlParam) // Send to server
      .done(function(data) {
        if (data.success) {
          $('#' + radio.id + 'Captain').show();
        }
        fade(document.getElementById('teamPlayer' + radio.id.replace('contactPlayer_id', '') + 'Span'), data.reason, data.success);
      })
      .fail(function(jqHXR,status,error) {
        debugOut('Fail: S: ' + status + ' E: ' + error); // Oh, no! Fail!
        debugOut(jqHXR.responseText);
      });
    } else {
      $('#' + radio.id + 'Captain').hide();      
    }
  });
}

// This is where the details form is validated. We won't validate much - just some basic stuff. Things like invalid email addresses and stuff is ok, we'll fix it in the database if necessary.
function checkForm(el) {
  var obj = player();
  for (var prop in classes[obj.class].fields) {
    if (prop != 'paid') {
      var meta = classes[obj.class].fields[prop]
      obj[prop] = document.getElementById(prop + ucfirst(meta.type)).value;
      if (meta.type == 'select') {
        if (meta.special == 'add') { // For selects we want to store the IDs, unless they added something
          if (document.getElementById(prop + 'AddText').value != '') {
            obj[prop] = document.getElementById(prop + 'AddText').value;
          }
        }
        obj[prop + '_id'] = obj[prop];
      } else if (meta.type == 'checkbox') {
        obj[prop] = document.getElementById(prop + ucfirst(meta.type)).checked;
      }
    }
  }
  // Check required fields:
  var valid = true;
  var error = '';
  if (!obj.main && !obj.classics) {
    $('#mainTd').removeClass('errorTd');
    $('#classicsTd').removeClass('errorTd');
    valid = false;
    error = 'You need to participate in at least one division';
    document.getElementById('mainTd').className += ' errorTd';
    document.getElementById('classicsTd').className += ' errorTd';
  }
  if (!obj.country_id && !obj.country) {
    $('#countryTd').removeClass('errorTd');
    valid = false;
    error = 'Country is required';
    document.getElementById('countryTd').className += ' errorTd';
  }
  for (var field in classes['player'].mandatory) {
    $('#' + classes['player'].mandatory[field] + 'Td').removeClass('errorTd');
    if (!obj[classes['player'].mandatory[field]] && classes['player'].mandatory[field] != 'country') {
      valid = false;
      error = classes['player'].mandatory[field] + ' is required';
      document.getElementById(classes['player'].mandatory[field] + 'Td').className += ' errorTd';
    }
  }
  if (valid) {
    submit(obj);
  } else {
    alert(error);
  }
}

function checkUsernameFields() {
  document.getElementById('submit').disabled = true;
  var usernameComplete = true;
  if (document.getElementById('action').value == 'changeUsername') {
    var usernameFields = ['username', 'currentPassword', 'newPassword', 'newPassword2'];
  }
  for (var field in usernameFields) {
    if (document.getElementById(usernameFields[field] + 'Text').value == '' || document.getElementById(usernameFields[field] + 'Span').innerHTML != '*') {
      usernameComplete = false;
    }
  }
  if (usernameComplete && usernameFields.indexOf('newPassword') && usernameFields.indexOf('newPassword2')) {
    if (document.getElementById('newPasswordText').value != document.getElementById('newPassword2Text').value) {
      usernameComplete = false;
      document.getElementById('newPassword2Span').innerHTML = '';
      document.getElementById('newPassword2Span').appendChild(document.createTextNode('Passwords don\'t match!'));
      $('#newPassword2Span').addClass('error'); // Add error 
    } else {
      document.getElementById('newPassword2Span').innerHTML = '';
      document.getElementById('newPassword2Span').appendChild(document.createTextNode('*'));
      $('#newPassword2Span').removeClass('error'); // Remove error 
    }
  }
  document.getElementById('submit').disabled = !usernameComplete;
  return usernameComplete;
}

function changeUsername(el) {
  if (checkUsernameFields()) {
    return true;
  } else {
    el.disabled = true;
    return false;
  }
}

function login(el) {
  if (document.getElementById('usernameLogin').value != '' && document.getElementById('passwordText').value != '') {
    document.getElementById('loginButton').disabled = false;
    return true;
  } else {
    document.getElementById('loginButton').disabled = true;
    return false;
  }
}

function ucfirst(txt) {
  return txt.toString().substring(0, 1).toUpperCase() + txt.toString().substring(1); // Why is this not a native part of Javascript? 
}

function complete(type) { // Used to check that all objects are completely loaded from ajax
  if (type && type != 'geo') {
    return (classes[type].complete); // Mark the type as complete
  } else {
    for (var item in classes) { // Check if all types are complete
      if (!complete(item)) {
        if (!(type == 'geo' && classes[item].name == 'player')) {
          return false; // Nope - still stuff to load.
        } 
      }
    }
    return true; // Yes, they are.
  }
}

function getObjects(type, obj, id, all) { // Load all objects from ajax. If type is specified, only that type is loaded. The function is recursive - if no type is specified, it will call itself for each type.
  obj = (obj) ? obj : $.url().param('obj');
  if (obj) {
    id = (id) ? id : $.url().param('id');
  }
  if (type == 'teams' || type == 'games') {
    classes.continent.complete = true;
    classes.country.complete = true;
    classes.region.complete = true;
    classes.city.complete = true;
    classes.manufacturer.complete = (type == 'teams') ? true : classes.manufacturer.complete;
    classes[((type == 'teams') ? 'game' : 'team')].complete = true;
    classes.player.complete = true;
  } else if (!type || type == 'geo') {
    classes.team.complete = true;
    classes.manufacturer.complete = true;
    classes.game.complete = true;
  }
  if (type && type != 'geo' && type != 'games' && type != 'teams') {
    var tourn = ($.url().param('t')) ? $.url().param('t') : ((all) ? 0 : 1);// Uuuh! Hard coded tournament ID!! Someone, hit me hard, please.
    var division = ($.url().param('d')) ? $.url().param('d') : null;
    showLoading(classes[type]); // Show a nice loading image
    var urlParams = {t: tourn, d: division, obj: obj, id: id};
    if (document.getElementById('national') && document.getElementById('national').value == '1') {
      urlParams.n = 1;
    }
    $.getJSON(baseHref + '/ajax/' + type + '.php', urlParams, function(data) {
      if (data && data.length > 0) {
        for (var obj in data) {
          new window[type.toUpperCase()](data[obj]); // Let's create objects from the JSON
        }
      }
      classes[type].complete = true; // Mark the type as complete. If true is returned, it was the last type.
      if (complete()) {
        popGeo(); // Let's get all parent-child relations straightened out.
        popEls($('select')); // Look for selects to populate
        popEls($('table')); // Look for tables to populate 
        popEls($('input')); // Look for inputs to populate (I don't think we ever do this)
      }
      if (complete('geo')) {
        if (document.getElementById('newButton')) {
          $('#ifpaButtonDiv').show()
          $('#newButtonDiv').show()
          $('#loadingObjects').hide()
          if (/^[0-9]{1,}$|.{3,}/.test(document.getElementById('ifpaIdText').value)) {
            document.getElementById('ifpaButton').disabled = false;
          }
          if (document.getElementById('nonPlayerLogin') && document.getElementById('nonPlayerLogin').value == 'true') {
            $.getJSON(baseHref + '/ajax/player.php',{id: document.getElementById('nonPlayerLoginId').value}) // Returns a JSON with the player
            .done(function(data) {
              var type = 'player';
              var objs = [];
              players.length = 0; // Let's delete all the players...
              if (data && data.length > 0) {
                for (var obj in data) {
                  new window[type.toUpperCase()](data[obj]); // ...and create new player objects from the JSON.
                }
                $('.loginTable').hide();
                $('#regDiv').hide();
                $('.regTitle').hide();
                thisIsMe(document.getElementById('meBtn_' + document.getElementById('nonPlayerLoginId').value));
              } else {
                $('#noHits').show();
              }
            })
            .fail(function(jqHXR,status,error) {
              debugOut('Fail: S: ' + status + ' E: ' + error); // Oh, no! Fail!
              debugOut(jqHXR.responseText);
            });
          }
          
        } else if ($.url().attr('file') == 'edit.php' && /^[0-9]+$/.test($.url().param('id'))) {
          editPlayer($.url().param('id')); // Player editing the profile = no recaptchas (player is logged in) and some other differences long the way
        } else if ($.url().segment(-1) == 'editplayer' && document.getElementById('id') && /^[0-9]+$/.test(document.getElementById('id').value)) {
          editPlayer(document.getElementById('id').value); // Same thing coming through wordpress
        } else if (document.getElementById('loggedIn') && document.getElementById('loggedIn').value == 'true' && document.getElementById('id') && /^[0-9]+$/.test(document.getElementById('id').value)) {
          editPlayer(document.getElementById('id').value); // Same thing coming through wordpress
        }
      }
    })
    .fail(function(jqHXR,status,error) {
      debugOut('Fail: S: ' + status + ' E: ' + error); // Oh, no! Fail!
      debugOut(jqHXR.responseText);
    });
  } else {
    if (type == 'games') {
      getObjects('manufacturer', obj, id);
      getObjects('game', obj, id);
    } else if (type == 'teams') {
      getObjects('team', obj, id);
    } else {
      for (var item in classes) { // The function is called for recursive action
        if (classes[item].name != 'game' && classes[item].name != 'manufacturer' && !(type == 'geo' && classes[item].name == 'player')) {
          getObjects(classes[item].name, obj, id); // ...so let's get recursive
        }
      }
    }
  }
}

function popEls(els) {
  for (var el in els) {
    switch (getType(els[el])) {
      case 'HTMLSelectElement': // We found a select!
        popSels(els); // ...so let's populate it
      break;
      case 'HTMLTableElement': // We found a table!
        popTbls(els); // ...so let's populate it
      break;
      case 'HTMLInputElement': // We found a input!
       // formatInputs(els); // ...so let's do nothing
      break;
    }
  }
}

function addOption(obj, sel, selected) { // Add an option to a select
  var option = document.createElement('option');
  option.value = obj.id;
  option.text = (obj.altName) ? obj.name + ' / ' + obj.altName : obj.name;
  if (sel) {
    sel.add(option); // Either add it to the provided select
    option.selected = (selected) ? true : false;
    option.setAttribute('previous', option.value);
  }
  return option; // ...or just return it
}

function addTypeOptions(sel, type) { // Add all the object of a specific type as options to the select
  sel.add(addOption({id: 0, name: 'Choose ' + type.name + '...'}));
  addOptions(sel, window[type.plural]);
}

function addOptions(sel, objs) { // Add options with the provided objects array
  if (objs.length > 0) {
    for (var obj in objs) {
      sel.add(addOption(objs[obj]));
    }
  } else {
    sel.innerHTML = '';
    sel.add(addOption({id: 0, name: 'None found...'})); // No objects provided!
  }
}


function selectOption(sel, id) { // Make the specific select option selected (ID is database ID, not HTML element ID)
  if (id) {
    for (var option in sel.options) {
      if (sel.options[option].value == id) {
        sel.options[option].selected = true;
        return true;
      }
    }
  } else {
     sel.options[0].selected = true
  }
}

function filterOptions (sel, obj) { // This will remove all non-appropriate options from a select. I.e. if someone chose "Europe", all non-european objects will be removed. If obj.id is 0 (unknown item), the select is in stead restored to show all options.
  sel.innerHTML = '';
  sel.add(addOption({id: 0, name: 'Loading...'})); // This might take some time - there are thousands of cities.
  showLoading(classes[sel.name]);
  var objs = (obj.id != 0) ? window[classes[sel.name].plural].filter(cmpGeo, obj) : window[classes[sel.name].plural];
   // It took a while to figure out the line above! sel.name is "country" or similar. classes['country'].plural is "countries". window['countries'] is the global countries array. And then apply the filter to get rid of unwanted objects.
  sel.innerHTML = '';
  sel.add(addOption({id: 0, name: 'Choose ' + sel.name + '...'}));
  addOptions(sel, objs); // Add the new options
  hideLoading(classes[sel.name]);
}

function cmpGeo(obj) {
    return (obj[this.class + '_id'] == this.id) ? true : false; // This is the filter used in the filterOptions function. It checks that object.country_id (or equivalent) is (or is not) the ID we are looking for.
}

function memberSelected(sel) {
  var num = sel.name.replace('teamPlayer', '').replace('Select', '');
  var captain = document.getElementById('contactPlayer_id' + num);
  var errorId = sel.id.replace('Select', '') + 'Span';
  if (checkMember(sel)) {
    captain.disabled = (sel.value == 0);
    checkMembers();
    if (sel.getAttribute('previous') > 0) {
      removeTeamMember(sel.getAttribute('previous'), errorId);
    }
    var playerId = sel.value;
    if (playerId > 0) {
      addTeamMember(playerId, errorId);
    } else {
      sel.setAttribute('previous', 0);
      $('#teamIncomplete' + num + 'Span').show();
    }
  } else {
    selectOption(sel, sel.getAttribute('previous'));
    fade(document.getElementById(errorId), 'Spelaren är ju redan med i laget! Välj en annan spelare.', false);
  }
}

function addTeamMember(playerId, errorId, adminTeamId) {
  adminTeamId = (adminTeamId) ? adminTeamId : false;
  if (playerId && playerId> 0) {
    var urlParams = {playerId: playerId};
  } else {
    return false;
  }
  if (adminTeamId) {
    urlParams.admin = 1;
    urlParams.teamId = adminTeamId.split('-')[0];
  } 
  fade(document.getElementById(errorId), 'Updaterar databasen...', true);
  $.post(baseHref + '/ajax/addTeamMember.php', urlParams) // Send to server
  .done(function(data) {
    setTimeout(function() {
      fade(document.getElementById(errorId), data.reason, data.success);
    }, 500);
    var sel = document.getElementById(errorId.replace('Span', ((adminTeamId) ? '' : 'Select')));
    if (data.success) {
      sel.setAttribute('previous', playerId);
      if (adminTeamId) {
        addOption({id: sel.id, name: sel.options[sel.selectedIndex].text}, document.getElementById(errorId.split('_')[0] + '_' + errorId.split('_')[1] + '_contactSelect'));
      } else {
        $('#' + errorId.replace('Player', 'Incomplete')).hide();
        var captain = document.getElementById(errorId.replace('teamPlayer', 'contactPlayer_id').replace('Span', ''));
        captain.value = sel.value;
        if (captain.value > 0 && captain.checked == true) {
          setTimeout(function() {
            setCaptain();
          }, 1000);
        }
      }
    } else {
      selectOption(sel, sel.getAttribute('previous'));
    }
  })
  .fail(function(jqHXR,status,error) {
    debugOut('Fail: S: ' + status + ' E: ' + error); // Oh, no! Fail!
    debugOut(jqHXR.responseText);
  });
}

function removeTeamMember(playerId, errorId, adminTeamId) {
  adminTeamId = (adminTeamId) ? adminTeamId : false;
  if (playerId && playerId> 0) {
    var urlParams = {playerId: playerId};
  } else {
    return false;
  }
  if (adminTeamId) {
    urlParams.admin = 1;
    urlParams.teamId = adminTeamId.split('-')[0];
    var idPrefix = adminTeamId + '_' + ((adminTeamId.split('-')[1] > 0) ? 'natTeam' : 'team');
  } else {
    if (playerId == document.getElementById('registerPerson_idHidden').value) {
      if(!confirm('Are you sure you want to leave the team? This can only be undone by another team member! If you are the last team member, the team will be deleted! The page will reload if you confirm.')) {
        return false;
      }
    }
  }
  fade(document.getElementById(errorId), 'Updaterar databasen...', true);
  $.post(baseHref + '/ajax/removeTeamMembers.php', urlParams) // Send to server
  .done(function(data) {
    fade(document.getElementById(errorId), data.reason, data.success);
    if (data.success) {
      if (adminTeamId) {
        var sel = document.getElementById(idPrefix + '_contactSelect');
        if (sel.value == playerId) {
          sel.removeChild(sel.options[sel.selectedIndex]);
          sel.selectedIndex = 0;
        } else {
          sel.removeChild(sel.options[sel.selectedIndex]);
        }
      } else {
        if (playerId == document.getElementById('registerPerson_idHidden').value) {
          setTimeout(function() {
            location.reload();
          }, 700);
        }
      }
    }
  })
  .fail(function(jqHXR,status,error) {
    debugOut('Fail: S: ' + status + ' E: ' + error); // Oh, no! Fail!
    debugOut(jqHXR.responseText);
  });
}

function deleteTeam(errorId) {
  fade(document.getElementById(errorId), 'Updaterar databasen...', true);
  $.post(baseHref + '/ajax/delTeam.php') // Send to server
  .done(function(data) {
    fade(document.getElementById(errorId), data.reason, data.success);    
    if (data.success) {
      setTimeout(function() {
        location.reload();
      }, 700);
    }
  })
  .fail(function(jqHXR,status,error) {
    debugOut('Fail: S: ' + status + ' E: ' + error); // Oh, no! Fail!
    debugOut(jqHXR.responseText);
  });
}

function checkMember(sel, adminPrefix) {
  adminPrefix = (adminPrefix) ? adminPrefix : false;
  for (var num = 2; num > 0; num--) {
    sel2 = document.getElementById(((adminPrefix) ? adminPrefix + 'memberSelect_' + num : 'teamPlayer' + num + 'Select'));
    if (sel != sel2 && sel.value != 0 && sel.value == sel2.value) {
      return false;
    }
  }
  return true;
}

function checkMembers() {
  for (var num = 2; num > 0; num--) {
    sel = document.getElementById('teamPlayer' + num + 'Select');
    if (sel.value == 0 || !checkMember(sel)) {
      $('#teamIncomplete' + num + 'Span').show();
      return false;
    }
  }
  $('.teamIncomplete').hide();
  return true;
}

function paymentMethodChange(el) {
  $('#domesticTable').hide();
  $('#internationalTable').hide();
  $('#paypalTable').hide();
  var paymentMethod = $('input[name=' + el.name + ']:checked').val();
  $('#' + el.value + 'Table').show();
}

function paymentChange(el) {
  var fields = ['main', 'classics', 'team', 'tShirt'];
  var totalCost = 0;
  var currency = document.getElementById('currency').value
  var items = [];
  var paid = document.getElementById('paid').value
  for (var field in fields) {
    var cost = (+ document.getElementById(fields[field] + 'Costs').value * document.getElementById(fields[field] + 'Cost').value);
    totalCost = (+ totalCost + cost);
    items[fields[field]] = document.getElementById(fields[field] + 'Costs').value;
    var curCost = Math.round((+ cost / currencies[currency].rate));
    document.getElementById(fields[field] + 'CostSpan').innerHTML = currency + ' ' + curCost;
  }
  totalCost = ((+ totalCost - paid) > 0) ? (+ totalCost - paid) : 0;
  var curTotalCost = Math.round((+ totalCost / currencies[currency].rate));
  document.getElementById('totalCost').value = curTotalCost;
  if (document.getElementById('curPaid')) {
    document.getElementById('curPaid').innerHTML = Math.round((+ paid / currencies[currency].rate));;
  }
  document.getElementById('totalCostSpan').innerHTML = currency + ' ' + curTotalCost;
  document.getElementById('domesticCosts').innerHTML = 'Betala' + currency + ' ' + curTotalCost + ' till något av följande:';
//  document.getElementById('internationalCosts').innerHTML = 'Please pay' + currency + ' ' + curTotalCost + ' to the account below:';
  document.getElementById('payPalAmount').value = curTotalCost + ' ' + currency;
  document.getElementById('payPalMsg').value = 'ID: ' + document.getElementById('idHidden').value + ' Main: ' + items['main'] + ' Classics: ' + items['classics'] + ' Teams: ' + items['team'] + ' T-shirts: ' + items['tShirt'];
}

function currencyChange(el) {
  var currency = $('input[name=' + el.name + ']:checked').val();
  document.getElementById('currency').value = currency;
  document.getElementById('payPalCurrency').value = currency;
  document.getElementById('payPalImg').src = baseHref + '/images/paypal_' + currency + '.gif';
  if (document.getElementById('paidCurrency')) {
    document.getElementById('paidCurrency').innerHTML = currency;
  }
  paymentChange(document.getElementById('mainCosts'));
}

function adminPlace(sel) {
  var playerId = sel.id.split('_')[0];
  var division = sel.id.split('_')[1];
  var wppr = (sel.id.split('_')[2] == 'wppr') ? 1 : 0;
  fade(document.getElementById(sel.id + 'Span'), 'Updaterar databasen...', true);
  $.post(baseHref + '/ajax/changePlace.php', {playerId: playerId, division: division, wppr: wppr, place: sel.value}) // Send to server
  .done(function(data) {
    fade(document.getElementById(sel.id + 'Span'), data.reason, data.success);    
    if (data.success) {
      sel.setAttribute('previous', sel.value);
    } else {
      selectOption(sel, sel.getAttribute('previous'));
    }
  })
  .fail(function(jqHXR,status,error) {
    debugOut('Fail: S: ' + status + ' E: ' + error); // Oh, no! Fail!
    debugOut(jqHXR.responseText);
  });
}

function adminGameEdit(icon, open) {
  if(open) {
    $('#' + icon.id + 'Div').show();
    if (document.getElementById(icon.id.replace('edit', 'comment'))) {
      document.getElementById(icon.id.replace('edit', 'comment')).focus();
    }
  } else {
    $('#' + icon.id.replace('Close', '')).hide();
  }
}


function adminGameComment(btn, open) {
  var machineId = btn.id.split('_')[0];
  var comment = document.getElementById(btn.id.replace('Submit', '')).value;
  fade(document.getElementById(btn.id + 'Span'), 'Updaterar databasen...', true);
  $.post(baseHref + '/ajax/gameComment.php', {machineId: machineId, comment: comment}) // Send to server
  .done(function(data) {
    fade(document.getElementById(btn.id + 'Span'), data.reason, data.success);
  })
  .fail(function(jqHXR,status,error) {
    debugOut('Fail: S: ' + status + ' E: ' + error); // Oh, no! Fail!
    debugOut(jqHXR.responseText);
  });
}


function adminGameDel(icon) {
  var machineId = icon.id.split('_')[0];
  fade(document.getElementById(icon.id + 'Span'), 'Updaterar databasen...', true);
  $.post(baseHref + '/ajax/gameDel.php', {machineId: machineId}) // Send to server
  .done(function(data) {
    fade(document.getElementById(icon.id + 'Span'), data.reason, data.success);
    if (data.success) {
      var tr = icon.parentNode.parentNode;
      $('#adminGameTable').dataTable().fnDeleteRow(tr);
    }
  })
  .fail(function(jqHXR,status,error) {
    debugOut('Fail: S: ' + status + ' E: ' + error); // Oh, no! Fail!
    debugOut(jqHXR.responseText);
  });
}

function adminGameNew(sel) {
  fade(document.getElementById(sel.id + 'Span'), 'Klicka på pluset till höger för att lägga till spelet.', true);
}

function adminGameUsage(sel) {
  var machineId = sel.id.split('_')[0];
  var divisionId = sel.value;
  fade(document.getElementById(sel.id + 'Span'), 'Updaterar databasen...', true);
  $.post(baseHref + '/ajax/gameUsage.php', {machineId: machineId, divisionId: divisionId}) // Send to server
  .done(function(data) {
    fade(document.getElementById(sel.id + 'Span'), data.reason, data.success);
    if (data.success) {
      sel.setAttribute('previous', sel.value);
    } else {
      selectOption(sel, sel.getAttribute('previous'));
    }
  })
  .fail(function(jqHXR,status,error) {
    debugOut('Fail: S: ' + status + ' E: ' + error); // Oh, no! Fail!
    debugOut(jqHXR.responseText);
  });
}

function adminGameType(sel) {
  var gameId = sel.id.split('_')[0];
  var type = sel.value;
  fade(document.getElementById(sel.id + 'Span'), 'Updaterar databasen...', true);
  $.post(baseHref + '/ajax/gameType.php', {gameId: gameId, type: type}) // Send to server
  .done(function(data) {
    fade(document.getElementById(sel.id + 'Span'), data.reason, data.success);    
    if (data.success) {
      sel.setAttribute('previous', sel.value);
    } else {
      selectOption(sel, sel.getAttribute('previous'));
    }
  })
  .fail(function(jqHXR,status,error) {
    debugOut('Fail: S: ' + status + ' E: ' + error); // Oh, no! Fail!
    debugOut(jqHXR.responseText);
  });
}

function adminGameOwner(sel) {
  var machineId = sel.id.split('_')[0];
  var ownerId = sel.value;
  fade(document.getElementById(sel.id + 'Span'), 'Updaterar databasen...', true);
  $.post(baseHref + '/ajax/gameOwner.php', {machineId: machineId, ownerId: ownerId}) // Send to server
  .done(function(data) {
    fade(document.getElementById(sel.id + 'Span'), data.reason, data.success);
    if (data.success) {
      sel.setAttribute('previous', sel.value);
    } else {
      selectOption(sel, sel.getAttribute('previous'));
    }
  })
  .fail(function(jqHXR,status,error) {
    debugOut('Fail: S: ' + status + ' E: ' + error); // Oh, no! Fail!
    debugOut(jqHXR.responseText);
  });
}

function adminGameBalls(sel) {
  var machineId = sel.id.split('_')[0];
  var balls = sel.value;
  fade(document.getElementById(sel.id + 'Span'), 'Updaterar databasen...', true);
  $.post(baseHref + '/ajax/gameBalls.php', {machineId: machineId, balls: balls}) // Send to server
  .done(function(data) {
    fade(document.getElementById(sel.id + 'Span'), data.reason, data.success);
    if (data.success) {
      sel.setAttribute('previous', sel.value);
    } else {
      selectOption(sel, sel.getAttribute('previous'));
    }
  })
  .fail(function(jqHXR,status,error) {
    debugOut('Fail: S: ' + status + ' E: ' + error); // Oh, no! Fail!
    debugOut(jqHXR.responseText);
  });
}

function adminGameExtraBalls(box) {
  var machineId = box.id.split('_')[0];
  var extraBalls = box.checked;
  fade(document.getElementById(box.id + 'Span'), 'Updaterar databasen...', true);
  $.post(baseHref + '/ajax/gameExtraBalls.php', {machineId: machineId, extraBalls: ((box.checked) ? 1 : 0), type: box.id.split('_')[1]}) // Send to server
  .done(function(data) {
    fade(document.getElementById(box.id + 'Span'), data.reason, data.success);
    if (!data.success) {
      box.checked = !box.checked;
    }
  })
  .fail(function(jqHXR,status,error) {
    debugOut('Fail: S: ' + status + ' E: ' + error); // Oh, no! Fail!
    debugOut(jqHXR.responseText);
  });
}

function adminGameOnePlayerAllowed(box) {
  var machineId = box.id.split('_')[0];
  var onePlayerAllowed = box.checked;
  fade(document.getElementById(box.id + 'Span'), 'Updaterar databasen...', true);
  $.post(baseHref + '/ajax/gameOnePlayerAllowed.php', {machineId: machineId, onePlayerAllowed: ((box.checked) ? 1 : 0), type: box.id.split('_')[1]}) // Send to server
  .done(function(data) {
    fade(document.getElementById(box.id + 'Span'), data.reason, data.success);
    if (!data.success) {
      box.checked = !box.checked;
    }
  })
  .fail(function(jqHXR,status,error) {
    debugOut('Fail: S: ' + status + ' E: ' + error); // Oh, no! Fail!
    debugOut(jqHXR.responseText);
  });
}

function adminGameAdd(icon) {
  var gameSel = document.getElementById(icon.id.replace('Add', ''));
  var gameId = gameSel.value;
  if (gameId != 0) {
    fade(document.getElementById(icon.id + 'Span'), 'Updaterar databasen...', true);
    $.post(baseHref + '/ajax/gameNew.php', {gameId: gameId}) // Send to server
    .done(function(data) {
      fade(document.getElementById(icon.id + 'Span'), data.reason, data.success);
      if (data.success) {
        var newRow = $('#adminGameTable').dataTable().fnAddData( [
          data.game,
          data.acro,
          data.manufacturer,
          data.owner,
          data.ipdb,
          data.rules,
          data.type,
          data.usage
        ]);
        var oSettings = $('#adminGameTable').dataTable().fnSettings();
        var nTr = oSettings.aoData[ newRow[0] ].nTr;
        for (var i = 0; i <= 7; i++) {
          $('td', nTr)[i].setAttribute('id', data.tdIds[i]);
        }
        selectOption(gameSel, 0);
      }
    })
    .fail(function(jqHXR,status,error) {
      debugOut('Fail: S: ' + status + ' E: ' + error); // Oh, no! Fail!
      debugOut(jqHXR.responseText);
    });
  }
}

function tshirtDlvrAll(box) {
  $('.' + box.id).prop('checked', box.checked);
  $('.' + box.id).each(function(){
    tshirtDlvr(this);
  });
}

function tshirtDlvr(box) {
  var playerTshirtId = box.id.split('_')[0];
  fade(document.getElementById(box.id + 'Span'), 'Updaterar databasen...', true);
  $.post(baseHref + '/ajax/tshirtDlvr.php', {playerTshirtId: playerTshirtId, dlvr: box.checked}) // Send to server
  .done(function(data) {
    fade(document.getElementById(box.id + 'Span'), data.reason, data.success);    
    if (!data.success) {
      box.checked = !box.checked;
    } else {
      if (document.getElementById(box.id + 'Date')) {
        document.getElementById(box.id + 'Date').innerHTML = (box.checked) ? 'Delivered ' + $.datepicker.formatDate('yy-mm-dd', new Date())  : 'Mark as delivered';
      }
      if (document.getElementById(box.id + 'Date')) {
        document.getElementById(box.id + 'Date').innerHTML = (box.checked) ? 'Delivered ' + $.datepicker.formatDate('yy-mm-dd', new Date())  : 'Mark as delivered';
      }
    }
  })
  .fail(function(jqHXR,status,error) {
    debugOut('Fail: S: ' + status + ' E: ' + error); // Oh, no! Fail!
    debugOut(jqHXR.responseText);
  });
}

function adminTshirtSold(icon) {
  var tshirtId = icon.id.split('_')[0];
  var action = (icon.id.split('_')[1] == 'tshirtAdd') ? 1 : -1 ;
  fade(document.getElementById(icon.id + 'Span'), 'Updaterar databasen...', true);
  $.post(baseHref + '/ajax/tshirtSold.php', {tshirtId: tshirtId, action: action}) // Send to server
  .done(function(data) {
    fade(document.getElementById(icon.id + 'Span'), data.reason, data.success);    
    if (data.success) {
      document.getElementById(tshirtId + '_tshirtSold').innerHTML = (+ parseInt(document.getElementById(tshirtId + '_tshirtSold').innerHTML) + action);
      document.getElementById(tshirtId + '_tshirtStock').innerHTML = (+ parseInt(document.getElementById(tshirtId + '_tshirtStock').innerHTML) - action);
      document.getElementById(tshirtId + '_tshirtForSale').innerHTML = (+ parseInt(document.getElementById(tshirtId + '_tshirtForSale').innerHTML) - action);
    }
  })
  .fail(function(jqHXR,status,error) {
    debugOut('Fail: S: ' + status + ' E: ' + error); // Oh, no! Fail!
    debugOut(jqHXR.responseText);
  }); 
}

function adminAlloc(icon, open) {
  if(open) {
    $('#' + icon.id + 'Edit').show();
  } else {
    $('#' + icon.id.replace('Close', '') + 'Edit').hide();
  }
}

function allocShowAll(box) {
  $('.allocEditIcon').each(function(){
    adminAlloc(this, box.checked);
  });
} 

function allocEdit(el, fader) {
  var taskId = el.id.split('_')[0];
  var periodId = el.id.split('_')[1];
  if ($(el).is(':checkbox')) {
    var change = (el.checked) ? 1 : 0;
    var urlParams = {taskId: taskId, periodId: periodId, change: change};
    $('.' + periodId + 'VolCheckbox').each(function(){
      if (this.id != el.id) {
        if (this.checked) {
          var oldChecked = this;
          if (document.getElementById(this.id.split('_')[0] + '_' + this.id.split('_')[1] + '_selfSlot')) {
            var oldP = document.getElementById(this.id.split('_')[0] + '_' + this.id.split('_')[1] + '_selfSlot');
            oldP.style.display = 'none';
          }
          this.checked = false;
        }
      }
    });
  } else {
    var playerId = el.value;
    var otherPlayerId = el.getAttribute('previous');
    var length = parseInt(document.getElementById(periodId + '_length').innerHTML.split(':')[0]);
    var urlParams = {taskId: taskId, periodId: periodId, playerId: playerId, otherPlayerId: otherPlayerId};
  }
  if (fader) {
    fade(document.getElementById(el.id + 'Span'), 'Updaterar databasen...', true);
  }
  $.post(baseHref + '/ajax/allocEdit.php', urlParams) // Send to server
  .done(function(data) {
    if (fader) {
      fade(document.getElementById(el.id + 'Span'), data.reason, data.success);
    }
    if (data.success) {
      if (!$(el).is(':checkbox')) {
        if(el.getAttribute('previous') == 0) {
          document.getElementById(taskId + '_' + periodId + '_alloc').innerHTML = (+ parseInt(document.getElementById(taskId + '_' + periodId + '_alloc').innerHTML) + 1);
        } else {
          var alloc = (parseInt(document.getElementById(otherPlayerId + '_alloc').innerHTML) > 0) ? parseInt(document.getElementById(otherPlayerId + '_alloc').innerHTML) : 0;
          document.getElementById(otherPlayerId + '_alloc').innerHTML = alloc - length;
          document.getElementById(otherPlayerId + '_diff').innerHTML =  (+  parseInt(document.getElementById(otherPlayerId + '_hours').value) - alloc - length);
        }
        if (playerId != 0) {
          var alloc = (parseInt(document.getElementById(playerId + '_alloc').innerHTML) > 0) ? parseInt(document.getElementById(playerId + '_alloc').innerHTML) : 0;
          document.getElementById(playerId + '_alloc').innerHTML = alloc + length;
          document.getElementById(playerId + '_diff').innerHTML = (+  parseInt(document.getElementById(playerId + '_hours').value) - alloc - length);
        }
        if(parseInt(document.getElementById(taskId + '_' + periodId + '_alloc').innerHTML) < parseInt(document.getElementById(taskId + '_' + periodId + '_needs').innerHTML)) {
          $('#' + taskId + '_' + periodId + '_needsTd').addClass('errorTd');
        } else {
          $('#' + taskId + '_' + periodId + '_needsTd').removeClass('errorTd');
        }
        el.setAttribute('previous', el.value);
      } else {
        if (el.checked) {
          if (document.getElementById(taskId + '_' + periodId + '_selfSlot')) {
            document.getElementById('selfSlots').removeChild(document.getElementById(taskId + '_' + periodId + '_selfSlot'));
          }
          var p = document.createElement('p');
          p.id = taskId + '_' + periodId + '_selfSlot';
          p.className = 'bold';
          setTimeout(function() {
            $(p).removeClass('bold');
          }, 3000);
          p.innerHTML = document.getElementById(taskId + '_' + periodId + '_selfSlotHidden').value
          document.getElementById('selfSlots').appendChild(p);
          $('#selfSlots').show();
        } else {
          if (document.getElementById(taskId + '_' + periodId + '_selfSlot')) {
            document.getElementById('selfSlots').removeChild(document.getElementById(taskId + '_' + periodId + '_selfSlot'));
          }
        }
      }
    } else {
      if (!$(el).is(':checkbox')) {
        selectOption(el, el.getAttribute('previous'));
      } else {
        el.checked = !el.checked;
        if (oldP) {
          oldChecked.checked = true;
          oldP.style.display = '';
        }
      }
    }
  })
  .fail(function(jqHXR,status,error) {
    debugOut('Fail: S: ' + status + ' E: ' + error); // Oh, no! Fail!
    debugOut(jqHXR.responseText);
  }); 

}

function changeNeed(sel) {
  var taskId = sel.id.split('_')[0];
  var periodId = sel.id.split('_')[1];
  var need = sel.value;
  fade(document.getElementById(sel.id + 'Span'), 'Updaterar databasen...', true);
  $.post(baseHref + '/ajax/changeNeed.php', {periodId: periodId, taskId: taskId, need: need}) // Send to server
  .done(function(data) {
    fade(document.getElementById(sel.id + 'Span'), data.reason, data.success);    
    if (data.success) {
      if(parseInt(document.getElementById(taskId + '_' + periodId + '_alloc').innerHTML) < need) {
        $('#' + sel.id + 'Td').addClass('errorTd');
      } else {
        $('#' + sel.id + 'Td').removeClass('errorTd');
      }
    }
  })
  .fail(function(jqHXR,status,error) {
    debugOut('Fail: S: ' + status + ' E: ' + error); // Oh, no! Fail!
    debugOut(jqHXR.responseText);
  });
}

function mobileNumberChange(btn) {
  var playerId = btn.id.split('_')[0];
  fade(document.getElementById(btn.id + 'Span'), 'Updaterar databasen...', true);
  $.post(baseHref + '/ajax/changeCell.php', {playerId: playerId, cell: document.getElementById(playerId + '_mobileNumberChange').value, admin: 1, tournament: 1}) // Send to server
  .done(function(data) {
    fade(document.getElementById(btn.id + 'Span'), data.reason, data.success);    
    if (data.success) {
      document.getElementById(playerId + '_mobileNumber').value = document.getElementById(playerId + '_mobileNumberChange').value;
    }
  })
  .fail(function(jqHXR,status,error) {
    debugOut('Fail: S: ' + status + ' E: ' + error); // Oh, no! Fail!
    debugOut(jqHXR.responseText);
  });
}

function adminHoursChange(sel) {
  var playerId = sel.id.split('_')[0];
  fade(document.getElementById(sel.id + 'Span'), 'Updaterar databasen...', true);
  $.post(baseHref + '/ajax/addHours.php', {playerId: playerId, h: sel.value, admin: 1, tournament: 1}) // Send to server
  .done(function(data) {
    fade(document.getElementById(sel.id + 'Span'), data.reason, data.success);    
    if (data.success) {
      sel.setAttribute('previous', sel.value);
      if (document.getElementById(playerId + '_diff')) {
        var alloc = (parseInt(document.getElementById(playerId + '_alloc').innerHTML) > 0) ? parseInt(document.getElementById(playerId + '_alloc').innerHTML) : 0;
        document.getElementById(playerId + '_diff').innerHTML = (+ sel.value - alloc);
      }
    } else {
      selectOption(sel, sel.getAttribute('previous'));
    }
  })
  .fail(function(jqHXR,status,error) {
    debugOut('Fail: S: ' + status + ' E: ' + error); // Oh, no! Fail!
    debugOut(jqHXR.responseText);
  });
}

function adminQualGroup(sel) {
  var playerId = sel.id.split('_')[0];
  var divisionId = sel.id.split('_')[1];
  fade(document.getElementById(sel.id + 'Span'), 'Updaterar databasen...', true);
  $.post(baseHref + '/ajax/setQualGroup.php', {playerId: playerId, divisionId: divisionId, qualGroup: sel.value}) // Send to server
  .done(function(data) {
    fade(document.getElementById(sel.id + 'Span'), data.reason, data.success);    
    if (data.success) {
      if (sel.getAttribute('previous') > 0) {
        document.getElementById(sel.getAttribute('previous') + '_assigned').innerHTML = (+ document.getElementById(sel.getAttribute('previous') + '_assigned').innerHTML - 1);
      }
      if (sel.value > 0) {
        document.getElementById(sel.value + '_assigned').innerHTML = (+ document.getElementById(sel.value + '_assigned').innerHTML + 1);
      }
      var otherSel = document.getElementById(sel.id.replace('_' + sel.id.split('_')[1] + '_', '_' + ((divisionId == 1) ? 2 : 1) + '_'));
      if (document.getElementById(sel.id + 'Chosen').value.split('_').indexOf(sel.value) != -1) {
        $('#' + sel.id + 'Td').addClass('yellow');
        $('#' + sel.id + 'Td').removeClass('errorTd');
        $('#' + sel.id + 'Td').removeClass('green');
      } else {
        $('#' + sel.id + 'Td').removeClass('yellow');
      }
      if (document.getElementById(otherSel.id + 'Chosen').value.split('_').indexOf(otherSel.value) != -1) {
        $('#' + otherSel.id + 'Td').addClass('yellow');
        $('#' + otherSel.id + 'Td').removeClass('errorTd');
        $('#' + otherSel.id + 'Td').removeClass('green');
      } else {
        $('#' + otherSel.id + 'Td').removeClass('yellow');
      }
      if (sel.value == document.getElementById(sel.id + 'Pref').value) {
        $('#' + sel.id + 'Td').addClass('green');
        $('#' + sel.id + 'Td').removeClass('errorTd');
        $('#' + sel.id + 'Td').removeClass('yellow');
      } else {
        $('#' + sel.id + 'Td').removeClass('green');
      }
      if (otherSel.value == document.getElementById(otherSel.id + 'Pref').value) {
        $('#' + otherSel.id + 'Td').addClass('green');
        $('#' + otherSel.id + 'Td').removeClass('errorTd');
        $('#' + otherSel.id + 'Td').removeClass('yellow');
      } else {
        $('#' + otherSel.id + 'Td').removeClass('green');
      }
      if (Math.abs(sel.value - otherSel.value) == 6) {
        $('#' + sel.id + 'Td').addClass('errorTd');
        $('#' + otherSel.id + 'Td').addClass('errorTd');
        $('#' + sel.id + 'Td').removeClass('green');
        $('#' + otherSel.id + 'Td').removeClass('green');
        $('#' + sel.id + 'Td').removeClass('yellow');
        $('#' + otherSel.id + 'Td').removeClass('yellow');
      } else {
        $('#' + sel.id + 'Td').removeClass('errorTd');
        $('#' + otherSel.id + 'Td').removeClass('errorTd');
      }
      sel.setAttribute('previous', sel.value);
    } else {
      selectOption(sel, sel.getAttribute('previous'));
    }
  })
  .fail(function(jqHXR,status,error) {
    debugOut('Fail: S: ' + status + ' E: ' + error); // Oh, no! Fail!
    debugOut(jqHXR.responseText);
  });
}

function adminAdmin(sel) {
  var playerId = sel.id.split('_')[0];
  fade(document.getElementById(sel.id + 'Span'), 'Updaterar databasen...', true);
  $.post(baseHref + '/ajax/setAdmin.php', {playerId: playerId, adminLevel: sel.value}) // Send to server
  .done(function(data) {
    fade(document.getElementById(sel.id + 'Span'), data.reason, data.success);    
    if (data.success) {
      sel.setAttribute('previous', sel.value);
    } else {
      selectOption(sel, sel.getAttribute('previous'));
    }
  })
  .fail(function(jqHXR,status,error) {
    debugOut('Fail: S: ' + status + ' E: ' + error); // Oh, no! Fail!
    debugOut(jqHXR.responseText);
  });
}

function volSize(sel) {
  fade(document.getElementById(sel.id + 'Span'), 'Updaterar databasen...', true);
  $.post(baseHref + '/ajax/volSize.php', {size: sel.value}) // Send to server
  .done(function(data) {
    fade(document.getElementById(sel.id + 'Span'), data.reason, data.success);
    if (data.success) {
      sel.setAttribute('previous', sel.value);
    } else {
      selectOption(sel, sel.getAttribute('previous'));
    }
  })
  .fail(function(jqHXR,status,error) {
    debugOut('Fail: S: ' + status + ' E: ' + error); // Oh, no! Fail!
    debugOut(jqHXR.responseText);
  });
}

function adminHere(box) {
  var playerId = box.id.split('_')[0];
  fade(document.getElementById(box.id + 'Span'), 'Updaterar databasen...', true);
  $.post(baseHref + '/ajax/setHere.php', {playerId: playerId, here: ((box.checked) ? 1 : 0), type: box.id.split('_')[1]}) // Send to server
  .done(function(data) {
    fade(document.getElementById(box.id + 'Span'), data.reason, data.success);    
    if (data.success) {
      var totalHere = document.getElementById('total_here');
      if (totalHere) {
        totalHere.innerHTML = (+ parseInt(totalHere.innerHTML) + ((box.checked) ? 1 : -1));
      }
    } else {
      box.checked = !box.checked;
    }
  })
  .fail(function(jqHXR,status,error) {
    debugOut('Fail: S: ' + status + ' E: ' + error); // Oh, no! Fail!
    debugOut(jqHXR.responseText);
  });
}

function adminPaidChange(sel) {
  var playerId = sel.id.split('_')[0];
  fade(document.getElementById(sel.id + 'Span'), 'Updaterar databasen...', true);
  $.post(baseHref + '/ajax/setPaid.php', {playerId: playerId, paid: ((sel.value) ? sel.value : sel.innerHTML)}) // Send to server
  .done(function(data) {
    fade(document.getElementById(sel.id + 'Span'), data.reason, data.success);    
    if (data.success) {
      if (sel.getAttribute('previous')) {
        sel.setAttribute('previous', sel.value);
        $('.' + sel.id.split('_')[0] + '_' + sel.id.split('_')[2]).each(function () {
          selectOption(document.getElementById($(this).attr('id')), sel.value);        
        });
      }
      if (document.getElementById(playerId + '_diff')) {
        document.getElementById(playerId + '_diff').innerHTML = (+ parseInt(document.getElementById(playerId + '_costs').innerHTML) - sel.value);
      }
      if (document.getElementById(playerId + '_tooMuchCosts')) {
        document.getElementById(playerId + '_costs').innerHTML = sel.innerHTML;
        $('.paymentCorrect').show();
        $('.paymentTooMuch').hide();
        $('.paymentNeeded').hide();
      }
    } else {
      if (sel.getAttribute('previous')) {
        selectOption(sel, sel.getAttribute('previous'));
      }
    }
  })
  .fail(function(jqHXR,status,error) {
    debugOut('Fail: S: ' + status + ' E: ' + error); // Oh, no! Fail!
    debugOut(jqHXR.responseText);
  });
}

function curCalc(sel) {
  for (var currency in currencies) {
    document.getElementById(currency).selectedIndex = sel.selectedIndex;
  }
}

function infoSelected(sel) {
  var type = sel.name.replace('all', '').replace('Select', '').toLowerCase();
  var id = sel.options[sel.selectedIndex].value;
  window.location.href = baseHref + '/?s=object&obj=' + type + '&id=' + id;
}

/*
  document.getElementById('infoTable').innerHTML = '<img src="' + baseHref + '/images/ajax-loader.gif" alt="Loading data...">';
  $.ajax(baseHref + '/ajax/getInfo.php?obj=' + type + '&id=' + id) // Returns a JSON with a new infoDiv
  .done(function(data) {
    document.getElementById('infoDiv').innerHTML = data;
    for (var item in classes) {
      classes[item].complete = false;
      if (document.getElementById(classes[item].name + 'Table')) {
        window[classes[item].plural].length = 0;
      }
      getObjects(classes[item].name, type, id);
    }
  })
  .fail(function(jqHXR,status,error) {
    debugOut('Fail: S: ' + status + ' E: ' + error); // Oh, no! Fail!
    debugOut(jqHXR.responseText);
  });
}    
*/

function geoSelected(sel) { // Someone chose something in a geo-select! The "sel" is the select that was changed, and targetSel below is the select that might be affected.
  var start = false; // What? We're just starting!
  for (var item in classes) {
    if (classes[item].geo) { // Only apply to geographical stuff
      var targetSel = document.getElementById(classes[item].name + 'Select'); // This is the select we want to change. 
      if (targetSel.name == sel.name) { // The loop has reached the select that initiated things. So we need to change method (from filtering to selecting, or vice versa).
        start = true; // Now we're starting!
      } else if (start) {
        if (targetSel.options[targetSel.selectedIndex].value != 0) { // Check if the user had already selected something (or if something was already in the database)
          if (window[targetSel.name](targetSel.options[targetSel.selectedIndex].value)[sel.name + '_id'] && window[targetSel.name](targetSel.options[targetSel.selectedIndex].value)[sel.name + '_id'] != '') { // Check if the object already had a parent?
            var oldParent = window[sel.name](window[targetSel.name](targetSel.options[targetSel.selectedIndex].value)[sel.name + '_id']);
            var geoMove = confirm(targetSel.options[targetSel.selectedIndex].text + ' was already selected, and located in ' + oldParent.name + '. Do you suggest moving ' + targetSel.options[targetSel.selectedIndex].text + ' to ' + sel.options[sel.selectedIndex].text + ', or do you want to reselect? (OK to move, cancel to reselect)');
            if (!geoMove) {
              filterOptions(targetSel, window[sel.name](sel.options[sel.selectedIndex].value)); // No move! Then we filter - i.e. if the user chose a country, the targetSel is regions or cities and will be filtered to only showing for that country
            } else {
              // Nothing here = means that the user can essentially move Stockholm to Oman. If that's not acceptable, checks could be implemented here.
            }
          } else {
            // This means that the object had no parent, but will get one when this user submits the data. Unless we implement something to stop it (but why?).
          }
        } else {
          filterOptions(targetSel, window[sel.name](sel.options[sel.selectedIndex].value)); // This is filtering - i.e. if the user chose a country, the targetSel is regions or cities
        }
      } else {
        if(window[sel.name](sel.options[sel.selectedIndex].value)[targetSel.name + '_id']) {
          if (!targetSel.disabled) { // Don't change disabled selects - they're disabled for a reason
            selectOption(targetSel, window[sel.name](sel.options[sel.selectedIndex].value)[targetSel.name + '_id']);
            // This is selecting - i.e. if the user chose a country, the targetsel is continents. Example, with country chosen and continents as targetSel:
            // window[sel.name] = country
            // sel.options[sel.selectedIndex].value = the ID of the country chosen, let's pick 188 (Sweden) as example
            // targetSel.name + '_id' = continent_id
            // Result: country(188).continent_id
            // The country(188) function will return the country object with ID 188 = Sweden
            // I.e. selectOption will select Sweden.continent_id - the continent that Sweden is located on = Europe
          }
        } else {
          selectOption(targetSel, 0); // There was no parent! So let's have the user choose one.
        }
      }
    }
  }
}

function geoAdd(el, add) { // Somebody wants to add (or regrets wanting to add depending on add = true/false) a geo-object. The "el" is the plus sign. 
  if (add) { // We're adding!
    // Let's change the label using some creative (and ugly) cut'n'pasting-replacing of element IDs
    document.getElementById(el.id.replace('Add', '') + 'Label').innerHTML = 'Ny ' + document.getElementById(el.id.replace('Add', '') + 'Label').innerHTML.toLowerCase();
    $('#' + el.id.replace('Add', '') + 'Select').hide(); // Hide the dropdown
    $('#' + el.id.replace('Add', '') + 'Span').hide(); // Hide the select span
    el.style.display = 'none'; // Hide the plus sign
    $('#' + el.id + 'Text').show(); // Show the text input field
    $('#' + el.id + 'Span').show(); // Show the input span
    $('#' + el.id + 'Cancel').show(); // Show the cancel icon
    $('#' + el.id + 'Text').focus(); // Put the focus on the text input
  } else { // We're regretting!
  // Some more creative (and ugly) cut'n'pasting-replacing of element IDs to change the label back
    document.getElementById(el.id.replace('AddCancel', '') + 'Label').innerHTML = ucfirst(document.getElementById(el.id.replace('AddCancel', '') + 'Label').innerHTML.replace('Ny ', ''));
    $('#' + el.id.replace('AddCancel', '') + 'Select').show(); // Show the dropdown again
    $('#' + el.id.replace('AddCancel', '') + 'Span').show(); // Show the select span again
    $('#' + el.id.replace('Cancel', '')).show(); // Show the plus sign again
    $('#' + el.id.replace('Cancel', '') + 'Text').hide(); // Hide the text input
    $('#' + el.id.replace('Cancel', '') + 'Span').hide(); // Hide the text span
    el.style.display = 'none'; // Hide the cancel icon
    document.getElementById(el.id.replace('Cancel', '') + 'Text').value = null; // We need to empty the text input, or the ajax call will think the user added something (Ajax can't see that it is hidden)
    $('#' + el.id.replace('AddCancel', '') + 'Select').focus(); // Put the focus on the dropdown
  }
}

function popSel(sel, type) { // Let's pop a sel!
  if (!sel && type) {
    var sel = document.getElementById(type.name + 'Select'); // No sel provided, but we've got the type. Hopefully, there's only one...
  } else if (sel && !type) {
    var type = classes[sel.id.replace('Select', '').toLowerCase()]; // Who needs a type when we've got the select?
  }
  if (sel && type) { // Both were provided (or we figured them out)
    sel.innerHTML = '';
    addTypeOptions(sel, type); // Add all the object of a specific type as options to the select
    hideLoading(type);
  }
}

function popSels(sels){ // Let's pop some sels
  if (!sels) {
    var sels = [];
    for (var item in classes) {
      if (document.getElementById(classes[item].name + 'Select')) {
        sels.push(document.getElementById(classes[item].name + 'Select')); // No selects provided, but don't worry - we'll figure it out. Hopefully, there's only one of each type...
      }
    }
  }
  for (var sel in sels) {
    if (getType(sels[sel]) == 'HTMLSelectElement') { // We've gathered all selects to be populate
      popSel(sels[sel]); // ...so let's populate them
    }
  }
}

function enterClick(id, e) { // Simple function to make enter in a text input click a button (id = button ID). Will this work in IE?
  e = e || window.event;
  if (e.which == 13 || e.keyCode == 13) {
    e.preventDefault();
    e.returnValue = false;
    document.getElementById(id).click();
  }
}

function popTbl(tbl, type) { // Let's populate a table
  if (!tbl && type) {
    var tbl = document.getElementById(type.name + 'Table'); // No table provided, but we've got the type. Hopefully, there's only one...
  } else if (tbl.id && !type) {
    var type = classes[tbl.id.replace('Table', '').toLowerCase()]; // Who needs a type when we've got the table?
  }
  if (tbl && type) { // Both were provided (or we figured them out)
    showLoading(type);
    try {
      $('#' + tbl.id).dataTable.fnClearTable();// Clear the datatable layout (and the table) if the datatable object exists...
    } catch(err) {
      if (tbl.getElementsByTagName('tbody')[0]) {
        tbl.removeChild(tbl.getElementsByTagName('tbody')[0]); // ...otherwise empty the whole table. (Nulling the table with datatable object attached will make datatable freak out.)
      }
    }
    addThead(tbl, type); // Add headers...
    var tbody = addTbody(tbl); // ...body...
    addRows(tbody, type, true); // ...and rows
    if ($.url().attr('file') == 'adminTools.php') {
      if (type.name == 'player') {
    		aoColumns = [
  	      {'sSortDataType': 'dom-link' },
  		  	null,
  	      {'sSortDataType': 'dom-link' },
  	      {'sSortDataType': 'dom-link' },
  	      {'sSortDataType': 'dom-link' },
  	      {'sSortDataType': 'dom-link' },
  	      {'sSortDataType': 'dom-link', 'sType': 'numeric'},
          null
        ];
      } else {
        aoColumns = null;
      }
      $('#' + tbl.id).dataTable({
        'bProcessing': true,
        'bDestroy': true,
        'bJQueryUI': true,
	  	  'sPaginationType': 'full_numbers',
        'iDisplayLength': 200,
    		'aoColumns': aoColumns,
        'aLengthMenu': [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']]
      }); // Rebuild the datatable
      $('#' + tbl.id).css('width', ''); // This is needed, or the table is butt ugly!
    } else {
      $('#' + tbl.id).tablesorter();
    }
    hideLoading(type);
  }
}

function popTbls(tbls){ // Let's populate some tables
  if (!tbls) {
    var tbls = [];
    for (var item in classes) {
      if (document.getElementById(classes[item].name + 'Table')) {
        tbls.push(document.getElementById(classes[item].name + 'Table')); // No tables provided, but don't worry - we'll figure it out. Hopefully, there's only one of each type...
      }
    }
  }
  for (var tbl in tbls) { // We've gathered all selects to be populate
    popTbl(tbls[tbl]); // ...so let's populate them
  }
}

function addThead(tbl, type, meBtn) { // meBtn is the "Här är jag!" button at the end of each row - it also needs a header
  var thead = tbl.createTHead();
  addTheaders(thead, type, meBtn); // The type is the meta object (scroll up) containing meta info about the classes - including what headers to use
}

function addTheaders (thead, type, meBtn) { // meBtn is the "This is me!" button at the end of each row - it also needs a header
  thead.innerHTML = '';
  var tr = thead.insertRow(-1);
  tr.className += ' header';
  for (var header in type.headers) { // Let's go through the headers form the meta information objects
    if (!meBtn || (meBtn && type.headers[header] != 'classics' && type.headers[header] != 'dateRegistered' && type.headers[header] != 'paid')) {
      var th = document.createElement('th');
      tr.appendChild(th)
      th.appendChild(document.createTextNode(type.fields[type.headers[header]].label + ': '));
    }
  }
  if (meBtn) { // "This is me!" should be shown
    var th = document.createElement('th');
    tr.appendChild(th)
    th.appendChild(document.createTextNode('Jag?'));
  }
  /* else {
    tr.appendChild(th)
    th.style.display = 'none';
  }
  */
  return thead;
}

function addTbody(tbl) {
  var tbody = tbl.appendChild(document.createElement('tbody'));
  return tbody;
}


// "link" true/false = generate links for linkable fields
// "meBtns" true/false = add the "This is me!" button at the end of each row
// "sels" true/false = make all suitable fields into selects containing all objects of that class, with the current one pre-selected (to let a player change his/her city, or similar).
function addRows(tbody, type, links, meBtns, sels, objs) {
  objs = (objs) ? objs : window[type.plural]; // If no objs were provided, use the type and get the global array with all objects of that type
  for (var obj in objs) {
    addRow(tbody, objs[obj], links, meBtns, sels);
  }
}

// "link" true/false = generate links for linkable fields
// "meBtns" true/false = add the "This is me!" button at the end of each row
// "sels" true/false = make all suitable fields into selects containing all objects of that class, with the current one pre-selected (to let a player change his/her city, or similar).
function addRow(tbody, obj, link, meBtn, sels) {
  var tr = tbody.insertRow(-1);
  var headers = classes[obj.class].headers; // Get the headers to use
  for (var header in headers) {
    if (!meBtn || (meBtn && headers[header] != 'classics' && headers[header] != 'dateRegistered' && headers[header] != 'paid')) {
      var td = tr.insertCell(-1);
//    td.className = 'tdFix'
      if ((typeof obj[headers[header]] !== 'undefined' && obj[headers[header]] != null) || obj[headers[header] + '_id']) { // Check that the header exist on this object
        obj.addParents(); // Make sure all parent-child relations are there
        var item = null;
        if (sels && obj[headers[header]].hasOwnProperty('name')) { // If there is a name on the content, it means that this header contains another object - so let's make a select
          item = document.createElement('select');
          item.id = headers[header] + 'Select';
          popSel(item); // Populate it with all objectofs  the specific type
          selectOption(item, obj[headers[header]].id); // Select the correct option
        } else {
          if (link) {
            if (!obj.links) {
              obj.addLinks(); // Make sure all links have been generated
            }
            item = (obj.links[headers[header]]) ? obj.links[headers[header]] : null; // If there is a link - let's use it
          }
          item = (item) ? item : ((obj[headers[header]].hasOwnProperty('name')) ? document.createTextNode(obj[headers[header]].name) : document.createTextNode(obj[headers[header]])); // There was no link - so let's just add the text (either a name - if this is an object, or the actual content - if this is a string)
        }
        item = (classes[obj.class].fields[headers[header]].type == 'checkbox') ? ((obj[headers[header]] == 1) ? document.createTextNode('Ja') : document.createTextNode('Nej')) : item; // Change checkbox to Ja/Nej in stead of 1/0
        if (obj[headers[header]] && obj[headers[header]].hasOwnProperty('name')) {
          if (obj[headers[header]].id > 0) {
            td.appendChild(item);
          }
        } else {
          td.appendChild(item);
        }
      }
    }
  }
  if (meBtn) { // This is me! button
    var btn = document.createElement('input');
    btn.id = 'meBtn_' + obj.id;
    btn.type = 'button';
    btn.value = 'Här är jag!';
    btn.onclick = function() { thisIsMe(this); return false; };
    var td = tr.insertCell(-1);
    td.className = 'tdFix'
    td.appendChild(btn);
  }
/* else {
    var td = tr.insertCell(-1);
    td.className = 'tdFix invisible'
  }
  */
}

function tShirtIcon(tbody) {
  var tr = tbody.insertRow(-1);
  var td = tr.insertCell(-1);
  var td = tr.insertCell(-1);
  var img = document.createElement('img');
  img.id = 'tShirt' + number;
  img.src = baseHref + '/images/add_icon.gif';
  img.className += ' icon';
  img.onclick = 'addTShirt(this);';
  img.alt = 'Click to add a T-shirt';
  img.title = img.alt;
  td.appendChild(img);
}

function calcTshirtCost() {
  document.getElementById('tshirtCostHidden').value = 0;
  $('.number').each(function () {
    document.getElementById('tshirtCostHidden').value = (+ parseInt(document.getElementById('tshirtCostHidden').value) + parseInt($(this).val()) * 100);
  });
  var cost = parseInt(document.getElementById('tshirtCostHidden').value);
  var totalCost = 'Total cost: ';
  for (var cur in currencies) {
    totalCost += (currencies[cur].symbolPlace == -1) ? currencies[cur].symbol : '';
    totalCost += Math.round((+ cost / currencies[cur].rate));
    totalCost += (currencies[cur].symbolPlace == 1) ? currencies[cur].symbol + ' / ' : ' / ';
  }
  totalCost = totalCost.replace(/ \/ $/,'');
  document.getElementById('tshirtCostSpan').innerHTML = totalCost;
}

function addTshirt() {
  var tournament = document.getElementById('tournamentHidden').value;
  $.ajax(baseHref + '/ajax/addTshirt.php?t=' + tournament) // Returns HTML with a new tshirt row
  .done(function(data) {
    var json = JSON.parse(data);
    if (document.getElementById('tshirtNoneSpan')) {
      document.getElementById('tshirtNoneSpan').parentNode.removeChild(document.getElementById('tshirtNoneSpan'));
    }
    var div = document.createElement('div');
    document.getElementById('tshirtOrderTr').parentNode.appendChild(div);
    div.id = json.trId;
    var tshirtP = document.createElement('p');
    div.appendChild(tshirtP);
    selectTypes = ['number', 'color', 'size'];
    for (var selectType in selectTypes) {
      for (var tdEl in json[selectTypes[selectType]]) {
        tshirtP.innerHTML += json[selectTypes[selectType]][tdEl];
      }
    }
    calcTshirtCost();
  })
  .fail(function(jqHXR,status,error) {
    debugOut('Fail: S: ' + status + ' E: ' + error); // Oh, no! Fail!
    debugOut(jqHXR.responseText);
  });  
}

function delTshirt(num) {
  fade(document.getElementById(num + '_tshirtSpan'), 'Updaterar databasen...', true);
  $.getJSON(baseHref + '/ajax/delTshirt.php?id=' + num) // Returns HTML with a new tshirt row
  .done(function(data) {
    if (data.success) {
      document.getElementById(num + '_tshirtTr').parentNode.removeChild(document.getElementById(num + '_tshirtTr'));
      calcTshirtCost();
    } else {
      fade(document.getElementById(num + '_tshirtSpan'), data.reason, data.success)
    }
  })
  .fail(function(jqHXR,status,error) {
    debugOut('Fail: S: ' + status + ' E: ' + error); // Oh, no! Fail!
    debugOut(jqHXR.responseText);
  });  
}

function tshirtChanged(el) {
  var id = el.id.split('_')[0];
  var numberSel = document.getElementById(id + '_tshirtNumberSelect');
  var number = numberSel.options[numberSel.selectedIndex].value;
  numberSel.options[numberSel.selectedIndex].defaultSelected = true;
  var colorSel = document.getElementById(id + '_tshirtColorSelect');
  var color = colorSel.options[colorSel.selectedIndex].value;
  colorSel.options[colorSel.selectedIndex].defaultSelected = true;
  var sizeSel = document.getElementById(id + '_tshirtSizeSelect');
  var size = sizeSel.options[sizeSel.selectedIndex].value;
  sizeSel.options[sizeSel.selectedIndex].defaultSelected = true;
  var tournament = document.getElementById('tournamentHidden').value;
  if (color != 0 && size != 0 && number != 0) {
    document.getElementById(id + '_tshirtSpan').innerHTML = '';
    var post = {t: tournament, number: number, color: color, size: size, id: id}
    fade(document.getElementById(id + '_tshirtSpan'), 'Updaterar databasen...', true);
    $.post(baseHref + '/ajax/changeTshirt.php', post) // Send to server
    .done(function(data) {
      fade(document.getElementById(id + '_tshirtSpan'), data.reason, data.success)
      calcTshirtCost();
    })
    .fail(function(jqHXR,status,error) {
      debugOut('Fail: S: ' + status + ' E: ' + error); // Oh, no! Fail!
      debugOut(jqHXR.responseText);
    });
  } else {
    fade(document.getElementById(id + '_tshirtSpan'), 'You have not chosen all options for these T-shirts!', false, 3000, 1000);
    calcTshirtCost();
  }
}

function fade(el, text, success, start, duration) {
  var text = (text) ? text : el.innerHTML;
  var start = (start) ? start : 6000;
  var duration = (duration) ? duration : 2000;
  var success = (typeof success === 'undefined') ? true : success;
  el.innerHTML = text;
  if ($(el).hasClass('toolTip')) {
    var img = document.createElement('img');
    img.src = baseHref + '/images/cancel.png';
    img.alt = 'Click to close';
    img.className = 'icon right';
    el.title = 'Click to close';
    $(el).addClass('pointer');
    el.appendChild(img);
  }
  $('#' + el.id).stop(true, true).show();
  if ((+ $('#' + el.id).offset().left + $('#' + el.id).outerWidth()) > $(window).width()) {
    var left = (+ $('#' + el.id).offset().left - ($('#' + el.id).offset().left + $('#' + el.id).outerWidth() - $(window).width()) - 40);
  } else {
    var left = $('#' + el.id).offset().left;
  }
  $('#' + el.id).offset({
    'left': left,
    'top': $('#' + el.id).offset().top
  });
  if (success) {
    $('#' + el.id).removeClass('error');
  } else {
    $('#' + el.id).addClass('error');
  }
  setTimeout(function() { 
    $('#' + el.id).fadeOut(duration);
  }, start);
  $('#' + el.id).click(function() {
    $(this).fadeOut(200);
  });
}

function volunteerHoursChanged(el) {
  var tournament = document.getElementById('tournamentHidden').value;
  var hours = el.value
  var post = {t: tournament, h: hours};
  fade(document.getElementById('volunteerHoursSpan'), 'Updaterar databasen...', true);
  $.post(baseHref + '/ajax/addHours.php', post) // Send to server
  .done(function(data) {
    fade(document.getElementById('volunteerHoursSpan'), data.reason, data.success)
  })
  .fail(function(jqHXR,status,error) {
    debugOut('Fail: S: ' + status + ' E: ' + error); // Oh, no! Fail!
    debugOut(jqHXR.responseText);
  });
}

function timeSlotChanged(el, type, id) {
  var tournament = document.getElementById('tournamentHidden').value;
  var change = (el.checked) ? 1 : 0;
  if (type == 'qualGroup') {
    var radio = document.getElementById(el.id.replace('Checkbox', 'Radio'));
    if (!el.checked) {
      radio.checked = false;
    }
    if (radio.checked) {
      return timeSlotPreferedChanged(radio, id);
    }
  }
  var post = {t: tournament, id: id, c: change};
  fade(document.getElementById(el.id.replace('Checkbox', 'Span')), 'Updaterar databasen...', true);
  $.post(baseHref + '/ajax/change' + ucfirst(type) + '.php', post) // Send to server
  .done(function(data) {
    fade(document.getElementById(el.id.replace('Checkbox', 'Span')), data.reason, data.success, 3000, 1000);
  })
  .fail(function(jqHXR,status,error) {
    debugOut('Fail: S: ' + status + ' E: ' + error); // Oh, no! Fail!
    debugOut(jqHXR.responseText);
  });
}

function periodCheckAll(el, date) {
  elClass = (date) ? date : 'periodCheckbox';
  $('.' + elClass).prop('checked', el.checked);
  $('.' + elClass).each(function(){
    if (document.getElementById($(this).attr('id')).disabled) {
      $(this).prop('checked', false);
    } else {
      if (!$(this).hasClass('periodDate')) {
        timeSlotChanged(this, 'period', this.id.replace('_periodCheckbox', ''));
      }
    }
  });
}

function qualGroupCheckAll(el, date) {
  elClass = (date) ? date : 'qualGroupCheckbox';
  $('.' + elClass).prop('checked', el.checked);
  $('.' + elClass).each(function(){
    if (document.getElementById($(this).attr('id')).disabled) {
      $(this).prop('checked', false);
    } else {
      if (!$(this).hasClass('qualGroupDate')) {
        timeSlotChanged(this, 'qualGroup', this.id.replace('_qualGroupCheckbox', ''));
      }
    }
  });
}

function timeSlotPreferedChanged(el, id) {
  if (el.checked) {
    var checkbox = document.getElementById(el.id.replace('Radio', 'Checkbox'));
    checkbox.checked = true;
  }
  var tournament = document.getElementById('tournamentHidden').value;
  var change = (el.checked) ? 1 : 0;
  var post = {t: tournament, id: id, c: change, pr: 1};
  fade(document.getElementById(el.id.replace('Radio', 'Span')), 'Updaterar databasen...', true);
  $.post(baseHref + '/ajax/changeQualGroup.php', post) // Send to server
  .done(function(data) {
    fade(document.getElementById(el.id.replace('Radio', 'Span')), data.reason, data.success);
  })
  .fail(function(jqHXR,status,error) {
    debugOut('Fail: S: ' + status + ' E: ' + error); // Oh, no! Fail!
    debugOut(jqHXR.responseText);
  }); 
}

function deNorm(el) {
  fade(document.getElementById(el.id + 'Span'), 'Denormalizing...', true);
  $.post(baseHref + '/ajax/deNorm.php') // Send to server
  .done(function(data) {
    fade(document.getElementById(el.id + 'Span'), data, true);
  })
  .fail(function(jqHXR,status,error) {
    debugOut('Fail: S: ' + status + ' E: ' + error); // Oh, no! Fail!
    debugOut(jqHXR.responseText);
  });
}

function adminTeam(el) {
  var urlParams = {teamId: el.id.split('_')[0], admin: 1};
  urlParams.national = (el.id.split('_')[1] == 'natTeam') ? 1 : 0;
  var idPrefix = urlParams.teamId + '_' + el.id.split('_')[1] + '_';
  var action = el.id.split('_')[2];
  urlParams.name = document.getElementById(idPrefix + 'name').value;
  urlParams.initials = document.getElementById(idPrefix + 'initials').value;
  urlParams.national = (el.id.split('_')[1] == 'natTeam') ? 1 : 0;
  if (urlParams.national) {
    urlParams.country_id = urlParams.teamId.split('-')[1];
    urlParams.teamId = urlParams.teamId.split('-')[0];
    var adminTeamId = el.id.split('_')[0];
  } else {
    var adminTeamId = el.id.split('_')[0] + '-0';
  }
  if (urlParams.teamId == 0 && action != 'add') {
    fade(document.getElementById(el.id + 'Span'), 'Please create the team first!', false);
    return false;
  }
  if (!urlParams.name || urlParams.name == '') {
    if ($(el).is("select")) {
      selectOption(el, el.getAttribute('previous'));
    }
    fade(document.getElementById(el.id + 'Span'), 'Please give the team a name!', false);
    return false;
  }
  switch (action) {
    case 'nameChange':
    case 'add':
      var ajaxFile = 'regTeam';
    break;
    case 'delete':
      var ajaxFile = 'delTeam';
    break;
    case 'regSelect':
      urlParams.registerPerson_id = el.value;
      var ajaxFile = 'regTeam';
    break;
    case 'contactSelect':
      urlParams.contactPlayer_id = el.value;
      var ajaxFile = 'regTeam';
    break;
    case 'memberSelect':
      if (checkMember(el, idPrefix)) {
        if (el.getAttribute('previous') > 0) {
          removeTeamMember(el.getAttribute('previous'), el.id + 'Span', adminTeamId);
        }
        var playerId = el.value;
        if (playerId > 0) {
          addTeamMember(playerId, el.id + 'Span', adminTeamId);
        } else {
          el.setAttribute('previous', 0);
        }
      } else {
        selectOption(el, el.getAttribute('previous'));
        fade(document.getElementById(el.id + 'Span'), 'Member is already on the team! Choose another player.', false);
      }
      return true;
    break;
    default:
      return false;
    break;
  }
  fade(document.getElementById(el.id + 'Span'), 'Updaterar databasen...', true);
  $.post(baseHref + '/ajax/' + ajaxFile + '.php', urlParams) // Send to server
  .done(function(data) {
    var reason = data.reason;
    if (data.success) {
      if (action == 'add') {
        if (data.reason.match(/^[0-9]+$/)) {
          teamId = data.reason;
          if (urlParams.national) {
            var newIdPrefix = data.reason + '-' + urlParams.country_id + '_' + el.id.split('_')[1] + '_';
          } else {
            var newIdPrefix = data.reason + '_' + el.id.split('_')[1] + '_';
          }
          reason = 'Team created!';
          $('#' + idPrefix + 'add').hide();
          $('#' + idPrefix + 'delete').show();
          $('#' + idPrefix + 'nameChange').show();
          var disabled = false;
        }
      } else if (action == 'delete') {
        if (urlParams.national) {
          var newIdPrefix = '0-' + urlParams.country_id + '_' + el.id.split('_')[1] + '_';
          $('#' + idPrefix + 'add').show();
          $('#' + idPrefix + 'delete').hide();
          $('#' + idPrefix + 'nameChange').hide();
          var disabled = true;
          document.getElementById(idPrefix + 'name').value = '';
          document.getElementById(idPrefix + 'initials').value = '';
          document.getElementById(idPrefix + 'regSelect').selectedIndex = 0;
          document.getElementById(idPrefix + 'contactSelect').selectedIndex = 0;
          for (var num = 1; num < 3; num++) {
            document.getElementById(idPrefix + 'memberSelect_' + num).selectedIndex = 0;
          }
        } else {
          el.parentNode.parentNode.parentNode.removeChild(el.parentNode.parentNode);
          return false;
        }
      } else if (action == 'contactSelect') {
        reason = el.options[el.selectedIndex].text + ' is now captain for ' + urlParams.name
      } else if (action == 'regSelect') {
        reason = el.options[el.selectedIndex].text + ' is now the registrator for ' + urlParams.name
      } else {
        return false;
      }
      if (action == 'add' || action == 'delete') {
        document.getElementById(idPrefix + 'regSelect').disabled = disabled;
        document.getElementById(idPrefix + 'contactSelect').disabled = disabled;
        var elements = ['name', 'initials', 'nameChange', 'delete', 'add', 'regSelect', 'contactSelect'];
        for (var element in elements) {
          document.getElementById(idPrefix + elements[element]).id = newIdPrefix + elements[element];
          if (document.getElementById(idPrefix + elements[element] + 'Span')) {
            document.getElementById(idPrefix + elements[element] + 'Span').id = newIdPrefix + elements[element] + 'Span';
          }
        }
        for (var num = 1; num < 3; num++) {
          document.getElementById(idPrefix + 'memberSelect_' + num).disabled = disabled;
          document.getElementById(idPrefix + 'memberSelect_' + num).id = newIdPrefix + 'memberSelect_' + num;
          document.getElementById(idPrefix + 'memberSelect_' + num + 'Span').id = newIdPrefix + 'memberSelect_' + num + 'Span';
        }
      }
    }
    fade(document.getElementById(el.id + 'Span'), reason, data.success);
  })
  .fail(function(jqHXR,status,error) {
    debugOut('Fail: S: ' + status + ' E: ' + error); // Oh, no! Fail!
    debugOut(jqHXR.responseText);
  });
}

function addIfpaLink(ifpa_id, ifpaRank) {
  var txt = document.createTextNode(ifpaRank);
  if (ifpa_id && ifpaRank && /^[0-9]+$/.test(parseInt($.trim(ifpa_id))) && /^[0-9]+$/.test(parseInt($.trim(ifpaRank)))) {
    var a = document.createElement('a');
    a.href = 'http://www.ifpapinball.com/player.php?player_id=' + ifpa_id;
    a.target = '_new';
    a.appendChild(txt);
    return a;
  } else {
    return txt;
  }
}

function addIpdbLink(ipdb_id) {
  var txt = document.createTextNode(ipdb_id);
  if (ipdb_id && /^[0-9]+$/.test(parseInt($.trim(ipdb_id)))) {
    var a = document.createElement('a');
    a.href = 'http://ipdb.org/machine.cgi?id=' + ipdb_id;
    a.target = '_new';
    a.appendChild(txt);
    return a;
  } else {
    return txt;
  }
}

function addRulesLink(link) {
  var txt = document.createTextNode('Regler');
  if (link) {
    var a = document.createElement('a');
    a.href = link;
    a.target = '_new';
    a.appendChild(txt);
    return a;
  } else {
    return txt;
  }
}

function addTypeLink(type) {
  if (type) {
    var txt = document.createTextNode(ucfirst(type));
    if (type == 'main' || type == 'classics' || type == 'team') {
       var a = document.createElement('a');
       switch (type) {
         case 'main':
           var d = 1;
         break;
         case 'classics':
           var d = 2;
         break;
         case 'team':
           var d = 3;
         break;
       }
       a.href = baseHref + '/?s=object&obj=game&d=' + d;
       a.appendChild(txt);
       return a;
    } else {
      return txt;
    }
  }
}

function addLink(obj) { // Create a link for an object. This will be rewritten.
  var txt = document.createTextNode(obj.name)
  if (obj && obj.id != 0) {
    var a = document.createElement('a');
//    var url = $.url().attr('source').replace($.url().segment(-1), obj.class);
//    url = url.split('?')[0];
    var url = baseHref + '/?s=object';
    a.href = url + '&obj=' + obj.class + '&id=' + obj.id + (($.url().param('debug')) ? '&debug=1' : '') + (($.url().param('t')) ? '&t=' + $.url().param('t') : '');
    a.appendChild(txt);
    return a;
  } else {
    return txt;
  }
}

function addParent(obj, type) { // Add a parent-child relationship. This should be rewritten to a nice and fancy loop using window[classes] or window[type] in stead of these childish switch statements!
  switch (type) { // First: Find parent and introduce it to the child
    case 'continent':
      var parent = obj.getContinent();
      obj.continent = parent;
    break;
    case 'country':
      var parent = obj.getCountry();
      obj.country = parent;
    break;
    case 'region':
      var parent = obj.getRegion();
      obj.region = parent;
    break;
    case 'city':
      var parent = obj.getCity();
      obj.city = parent;
    break;
  }
  if (parent) { // Second: Introduce the child to the parent
    switch (obj.class) {
      case 'country':
        if (parent.countries.indexOf(obj) == -1) {
          parent.countries.push(obj);
        }
      break;
      case 'region':
        if (parent.regions.indexOf(obj) == -1) {
          parent.regions.push(obj);
        }
      break;
      case 'city':
        if (parent.cities.indexOf(obj) == -1) {
          parent.cities.push(obj);
        }
      break;
      case 'player':
        if (parent.players.indexOf(obj) == -1) {
          parent.players.push(obj);
        }
      break;
    }
  }
}

function unknown(type) { // Create a dummy object containing empty properties
  return new window[type.name.toUpperCase()]({
    class: type,
    id: 0,
    name: 'Unknown ' + type.name
  });
}

function findObject(id, type) { // Find an object using the ID and the class
  var objs = window[type.plural];
  for (var obj in objs) {
    if (objs[obj].id == id) {
      return objs[obj];
    }
  }
  return unknown(type); // None found!
}

function continent(id) { // Get the continent object with the specified ID
  if (getType(id) != 'Null' && getType(id) != 'Undefined') {
    return (id) ? findObject(id, classes.continent) : continents[continents.length -1] || false;
  } else {
    return unknown(classes.continent); // None found!
  }
}

function country(id) { // Get the country object with the specified ID
  if (getType(id) != 'Null' && getType(id) != 'Undefined') {
    return (id) ? findObject(id, classes.country) : countries[countries.length -1] || false;
  } else {
    return unknown(classes.country); // None found!
  }
}

function region(id) { // Get the region object with the specified ID
  if (getType(id) != 'Null' && getType(id) != 'Undefined') {
    return (id) ? findObject(id, classes.region) : regions[regions.length -1] || false;
  } else {
    return unknown(classes.region); // None found!
  }
}

function city(id) { // Get the city object with the specified ID
  if (getType(id) != 'Null' && getType(id) != 'Undefined') {
    return (id) ? findObject(id, classes.city) : cities[cities.length -1] || false;
  } else {
    return unknown(classes.city); // None found!
  }
}

function game(id) { // Get the city object with the specified ID
  if (getType(id) != 'Null' && getType(id) != 'Undefined') {
    return (id) ? findObject(id, classes.game) : games[games.length -1] || false;
  } else {
    return unknown(classes.game); // None found!
  }
}

function manufacturer(id) { // Get the city object with the specified ID
  if (getType(id) != 'Null' && getType(id) != 'Undefined') {
    return (id) ? findObject(id, classes.manufacturer) : manufacturers[manufacturers.length -1] || false;
  } else {
    return unknown(classes.manufacturer); // None found!
  }
}

function player(id) { // Get the player object with the specified ID
  if (getType(id) != 'Null' && getType(id) != 'Undefined') {
    return (id) ? findObject(id, classes.player) : players[players.length -1] || false;
  } else {
    return unknown(classes.player); // None found!
  }
}

function gender(id) { // Get the gender object with the specified ID
  if (getType(id) != 'Null' && getType(id) != 'Undefined') {
    return (id) ? findObject(id, classes.gender) : genders[genders.length -1] || false;
  } else {
    return unknown(classes.gender); // None found!
  }
}

function team(id) { // Get the team object with the specified ID
  if (getType(id) != 'Null' && getType(id) != 'Undefined') {
    return (id) ? findObject(id, classes.team) : teams[teams.length -1] || false;
  } else {
    return unknown(classes.team); // None found!
  }
}

function hideLoading(type) {
  if (type.name) {
    if (document.getElementById(type.name + 'Loading')) {
      $('#' + type.name + 'Loading').hide();
    }
    if (document.getElementById(type.name + 'Table_wrapper')) {
      $('#' + type.name + 'Table_wrapper').show()
    }
  }
}

function showLoading(type) {
  if (type.name) {
    if (document.getElementById(type.name + 'Loading')) {
      $('#' + type.name + 'Loading').show();
    }
    if (document.getElementById(type.name + 'Table_wrapper')) {
      $('#' + type.name + 'Table_wrapper').hide()
    }
  }
}

function popGeo(type) { // What is this doing?? It's recursive, it creates a variable, puts objects into it, returns nothing, and then the variable is destroyed when the function is finished? But why?
  if (type) {
    var objs = window[type.plural];
  } else {
    for (var item in classes) {
      popGeo(classes[item]);
    }
  }
}

function loadScript(url) { // Generic function for javascript loading. Don't think we ever use this.
  if (scripts.indexOf(url) == -1) {
    var js = document.createElement('script');
    js.src = url;
    js.type='text/javascript';
    document.getElementsByTagName('head')[0].appendChild(js);
    scripts.push(url)
  }
}

function getType(obj) { // Get a nicer variable type notation than the native Javascript one (everything is a freaking object in Javascript!)
 return ({}).toString.call(obj).match(/\s([a-zA-Z]+)/)[1];
}

function htmlEntitiesEncode(str) { // Generic function for html entities. Don't think we ever use this.
  var div = document.createElement('div');
  var text = document.createTextNode(str);
  div.appendChild(text);
  return div.innerHTML;
}

function htmlEntitiesDecode(str) { // Generic function for html entities. Don't think we ever use this.
  var ta = document.createElement('textarea');
  ta.innerHTML = str.replace(/</g,'&lt;').replace(/>/g,'&gt;');
  return ta.value;
}

function varDump(obj, name, tab) { // Debug function that recursively walk through a variable. Might hit the roof though, and then show nothing.
  if(1) {
    var tab = tab || '';
    var content = (name) ? tab + name + ' => ' : '';
    switch (getType(obj)) {
      case 'Array':
        content += 'Array[\n';
        for (var prop in obj) {
          content += varDump(obj[prop], prop, tab + '\t');
        }
        content += tab + ']';
      break;
      case 'Object':
        content += 'Object{\n';
        for (var prop in obj) {
          if (['prevObject'].indexOf(prop) == -1) {
            content += varDump(obj[prop], prop, tab + '\t');
          }
        }
        content += tab + '}';
      break;
      case 'HTMLCollection':
        content += 'Collection{\n';
        for (var prop in obj) {
          content += varDump(obj[prop], prop, tab + '\t');
        }
        content += tab + '}';
      break;
      case 'NodeList':
        content += 'Nodes{\n';
        for (var prop in obj) {
          content += varDump(obj[prop], prop, tab + '\t');
        }
        content += tab + '}';
      break;
      case 'HTMLTableSectionElement':
        content += 'TableElement{\n';
        for (var prop in obj) {
          if (['rows', 'id', 'name', 'className'].indexOf(prop) != -1) {
            content += varDump(obj[prop], prop, tab + '\t');
          }
        }
        content += tab + '}';
      break;
      case 'HTMLTableRowElement':
        content += 'Rows{\n';
        for (var prop in obj) {
          if (['cells', 'id', 'name', 'className'].indexOf(prop) != -1) {
            content += varDump(obj[prop], prop, tab + '\t');
          }
        }
        content += tab + '}';
      break;
      case 'HTMLLabelElement':
        content += 'Label<\n';
        for (var prop in obj) {
          if (['htmlFor', 'childNodes', 'id', 'name', 'className'].indexOf(prop) != -1) {
            content += varDump(obj[prop], prop, tab + '\t');
          }
        }
        content += tab + '>';
      break;
      case 'HTMLSelectElement':
        content += 'Select<\n';
        for (var prop in obj) {
          if (getType(obj[prop]) === 'HTMLOptionElement' || ['selectedIndex', 'length', 'size', 'disabled', 'id', 'name', 'className', 'previous'].indexOf(prop) != -1) {
            content += varDump(obj[prop], prop, tab + '\t');
          }
        }
        content += tab + '>';
      break;
      case 'HTMLTableCellElement':
        content += 'Cell<\n';
        for (var prop in obj) {
          if (['width', 'rowSpan', 'colSpan', 'childNodes', 'id', 'name', 'className'].indexOf(prop) != -1) {
            content += varDump(obj[prop], prop, tab + '\t');
          }
        }
        content += tab + '>';
      break;
      case 'HTMLInputElement':
        content += 'Input<\n';
        for (var prop in obj) {
          if (['value', 'type', 'name', 'id', 'checked', 'className'].indexOf(prop) != -1) {
            content += varDump(obj[prop], prop, tab + '\t');
          }
        }
        content += tab + '>';
      break;
      case 'HTMLOptionElement':
        content += 'Option< ' + obj.value + ' = \'' + obj.text + '\' >';
      break;   
      case 'HTMLTableElement':
        content += 'Table<\n';
        for (var prop in obj) {
          if (['tBodies', 'width', 'tHead', 'tFoot', 'id', 'name', 'className'].indexOf(prop) != -1) {
            content += varDump(obj[prop], prop, tab + '\t');
          }
        }
        content += tab + '>';
      break;
      case 'String':
        content += 'String: \'' + obj + '\'';
      break;
      case 'Number':
        content += 'Number: ' + obj;
      break;
      case 'Null':
        content += 'NULL';
      break;
      case 'Undefined':
        content += 'UNDEFINED';
      break;
      case 'Date':
        content += 'Date' + obj.toISOString() + ' ' + obj.getTimezoneOffset();
      break;
      case 'Boolean':
        content += 'Boolean: ' + obj.toString().toUpperCase();
      break;
      case '[object RegExp]':
        content += 'RegExp: /' + obj.source + '/';
      break;
      case 'Function':
        content += obj.toString().replace(/  /g, tab);
      break;
      case 'Text':
        content += 'Text: ' + obj.wholeText;
      break;
      case 'HTMLAnchorElement':
        content += 'Link<\n';
        content += (obj.id) ? tab + '\tID => ' + obj.id + '\n' : '';
        content += (obj.name) ? tab + '\tName => ' + obj.name + '\n' : '';
        content += tab + '\tHref => ' + obj.href + '\n';
        content += tab + '\tString: => ' + obj.text + '\n';
        content += tab + '>';
      break;
      default:
        content += getType(obj) + ': ' + obj;
      break;
    }
    return content + '\n';
  }
}

function debugOut(obj, name) { // Send some info to the debug div, if debugMode is true. Which, right now, it always is...
  var debugMode = $.url().param('debug');
  debugMode = true;
  if (debugMode) {
    var pre = document.createElement('pre');
    pre.innerHTML += varDump(obj, name);
    pre.style.align='left';
    document.getElementById('debug').appendChild(pre);
    console.log(obj);
  }
}


