# ~/.bashrc: executed by bash(1) for non-login shells.

# Note: PS1 and umask are already set in /etc/profile. You should not
# need this unless you want different defaults for root.
# PS1='${debian_chroot:+($debian_chroot)}\h:\w\$ '
# umask 022

# You may uncomment the following lines if you want `ls' to be colorized:
export LS_OPTIONS='--color=auto'
# eval "$(dircolors)"
# alias ls='ls $LS_OPTIONS'
# alias ll='ls $LS_OPTIONS -l'
# alias l='ls $LS_OPTIONS -lA'

alias ll='ls -alF'
alias la='ls -A'
alias l='ls -CF'
alias s='source ~/.bashrc'
alias se='vi ~/.bashrc && source ~/.bashrc'
alias webroot="cd /var/www/html"
alias website="cd /var/www/html/web"
alias webconf="cd /var/www/html/web/sites/default"
alias webstyles="cd /var/www/html/web/themes/custom/nass"
alias files="cd /var/www/html/web/sites/default/files"

#
# Some more alias to avoid making mistakes:
# alias rm='rm -i'
# alias cp='cp -i'
# alias mv='mv -i'

# alias ..='cd ..'

export PATH="/var/www/html/vendor/drush/drush:$PATH"

parse_git_branch() {
 git branch 2> /dev/null | sed -e '/^[^*]/d' -e 's/* \(.*\)/ (\1)/'
}

export PS1="${debian_chroot:+($debian_chroot)}\[\033[01;32m\]\u@\h\[\033[00m\]:\[\033[01;34m\]\w\[\033[00m\]\[\033[32m\]\$(parse_git_branch)\[\033[00m\]$ "
