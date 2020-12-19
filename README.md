# Entrata Floorplans API

This plugin makes it as simple as adding a shortcode to pull in floorplan listings for a property from the Entrata API. The only thingds you'll really need are a username and password for Entrata.

tldr;

Here are the two versions of the shortcode you're most likely to use:

```
[entrata username={USERNAME} password={PASS} propertyid={ID} leaseurl={URL} filters=true gform_id={ID}]
```

```
[entrata username={USERNAME} password={PASS} propertyid={ID} leaseurl={URL} limit=3 gform_id={ID}]
```

## Getting started

* Install the plugin (just download the zip file of the latest release from the "releases" page.
* Add the shortcode wherever you'd like floorplans to appear. Since you probably don't know the property ID offhand, just add something like this to start:

```
[entrata username={USERNAME} password={PASSWORD}]
```

When you've done that, just save the page and go to the frontend, where a helpful table will show you all of the properties you have access to and their corresponding property IDs (these should be numbers, with no letters).

So now, add that and your shortcode should look something like this:

```
[entrata username={USERNAME} password={PASS} propertyid={ID}]
```

You've done it! That should be pulling in all of the floorplans for the property.

## Parameters

There are three other parameters you'll almost certainly want to add.

### Lease URL

The leaseurl parameter will add "lease" buttons to each property, and they go to an arbitrary link. Using the shortcode above, that will look something like this:

```
[entrata username={USERNAME} password={PASS} propertyid={ID} leaseurl=https://blueridge-apts.com/lease/]
```

### Filters

If you'd like to automatically add filters based on the number of rooms, just add a filter parameter, like so:

```
[entrata username={USERNAME} password={PASS} propertyid={ID} filters=true]
```

### Limit

If you'd like to only show a few floorplans (say, on the home page), you can do that with a limit parameter. *NOTE: if you use both the limit parameter and the filter parameter, this will likely lead to unexpected behavior, so don't do that.*

```
[entrata username={USERNAME} password={PASS} propertyid={ID} limit=6]
```

### Columns

Most of the time, you'll be adding three columns. That's the default, so you don't need a parameter for that. If you'd like to change that number, you can do that with the columns parameter.

```
[entrata username={USERNAME} password={PASS} propertyid={ID} columns=3]
```

### Only showing floorplans with certain names

Sometimes, a property might have several subproperties that you want to show listings for individually. E.g. Maddie is the property, but "Walker" is in the name of all of the Walker floorplans – and we only want to show those in a certain place. You can do that like this (please note the capitalization *does* matter here).

```
[entrata username={USERNAME} password={PASS} propertyid={ID} onlyshow=Walker]
```
