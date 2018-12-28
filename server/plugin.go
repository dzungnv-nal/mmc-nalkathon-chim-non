package main

import (
	"encoding/json"
	"fmt"
	"net/http"
	"sync"

	"github.com/mattermost/mattermost-server/plugin"
)

type Plugin struct {
	plugin.MattermostPlugin

	// configurationLock synchronizes access to the configuration.
	configurationLock sync.RWMutex

	// configuration is the active plugin configuration. Consult getConfiguration and
	// setConfiguration for usage.
	configuration *configuration
}

func (p *Plugin) OnActivate() error {
	config := p.getConfiguration()
	if err := config.IsValid(); err != nil {
		return err
	}

	return nil
}

func (p *Plugin) ServeHTTP(c *plugin.Context, w http.ResponseWriter, r *http.Request) {
	config := p.getConfiguration()
	if err := config.IsValid(); err != nil {
		http.Error(w, "This plugin is not configured.", http.StatusNotImplemented)
		return
	}
	switch path := r.URL.Path; path {
	case "/get-configs":
		p.getPluginConfigs(w, r)
	case "/":
		fmt.Fprint(w, "Hello, world!")
	default:
		http.NotFound(w, r)
	}
}

func (p *Plugin) getPluginConfigs(w http.ResponseWriter, r *http.Request) {
	config := p.getConfiguration()
	if err := config.IsValid(); err != nil {
		http.Error(w, "This plugin is not configured.", http.StatusNotImplemented)
		return
	}
	userID := r.Header.Get("Mattermost-User-Id")
	if userID == "" {
		http.Error(w, "Not authorized", http.StatusUnauthorized)
		return
	}
	pluginConf, err := json.Marshal(config)
	if err != nil {
		fmt.Printf("Marshal failed: %s", err)
	}

	w.Write([]byte(fmt.Sprintf("%v", string(pluginConf))))
}

// See https://developers.mattermost.com/extend/plugins/server/reference/
